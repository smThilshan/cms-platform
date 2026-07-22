import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { DragDropContext, Droppable, Draggable, type DropResult } from '@hello-pangea/dnd';
import { getAdminMenu, reorderMenuItems, createMenuItem, deleteMenuItem } from '../../../api/menu';
import type { MenuItem } from '../../../types';
import PrivilegeGate from '../../../components/PrivilegeGate';
import Button from '../../../components/ui/Button';
import Input from '../../../components/ui/Input';

export default function MenuManager() {
  const queryClient = useQueryClient();
  const [newTitle, setNewTitle] = useState('');
  const [isAdding, setIsAdding] = useState(false);

  const { data: menuData, isLoading } = useQuery({
    queryKey: ['admin-menu'],
    queryFn: () => getAdminMenu().then(r => r.data.data),
  });

  const reorderMutation = useMutation({
    mutationFn: reorderMenuItems,
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['admin-menu'] }),
  });

  const createMutation = useMutation({
    mutationFn: () => createMenuItem({ title: newTitle }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['admin-menu'] });
      setNewTitle('');
      setIsAdding(false);
    },
  });

  const deleteMutation = useMutation({
    mutationFn: deleteMenuItem,
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['admin-menu'] }),
  });

  const onDragEnd = (result: DropResult) => {
    if (!result.destination || !menuData) return;

    const items = Array.from(menuData);
    const [moved] = items.splice(result.source.index, 1);
    items.splice(result.destination.index, 0, moved);

    const reordered = items.map((item, index) => ({
      id: item.id,
      order: index + 1,
      parent_id: item.parent_id,
    }));

    reorderMutation.mutate(reordered);
  };

  if (isLoading) return <div className="text-gray-500">Loading...</div>;

  return (
    <div>
      <div className="flex items-center justify-between mb-6">
        <h1 className="text-2xl font-bold text-gray-900">Menu Manager</h1>
        <PrivilegeGate privilege="menu.create">
          <Button onClick={() => setIsAdding(true)}>Add Item</Button>
        </PrivilegeGate>
      </div>

      {isAdding && (
        <div className="bg-white rounded-xl border border-gray-200 p-4 mb-4 flex gap-3">
          <Input
            placeholder="Menu item title"
            value={newTitle}
            onChange={e => setNewTitle(e.target.value)}
            className="flex-1"
          />
          <Button onClick={() => createMutation.mutate()} isLoading={createMutation.isPending}>
            Save
          </Button>
          <Button variant="secondary" onClick={() => setIsAdding(false)}>Cancel</Button>
        </div>
      )}

      <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <DragDropContext onDragEnd={onDragEnd}>
          <Droppable droppableId="menu">
            {(provided) => (
              <ul ref={provided.innerRef} {...provided.droppableProps} className="divide-y divide-gray-100">
                {menuData?.map((item: MenuItem, index: number) => (
                  <Draggable key={item.id} draggableId={String(item.id)} index={index}>
                    {(provided, snapshot) => (
                      <li
                        ref={provided.innerRef}
                        {...provided.draggableProps}
                        className={`flex items-center gap-3 px-4 py-3 ${snapshot.isDragging ? 'bg-indigo-50 shadow-md' : 'hover:bg-gray-50'}`}
                      >
                        <PrivilegeGate privilege="menu.reorder">
                          <span
                            {...provided.dragHandleProps}
                            className="text-gray-400 cursor-grab select-none text-lg"
                            title="Drag to reorder"
                          >
                            ⠿
                          </span>
                        </PrivilegeGate>
                        <span className="flex-1 text-sm font-medium text-gray-800">{item.title}</span>
                        <span className="text-xs text-gray-400">{item.children?.length ?? 0} children · {item.pages?.length ?? 0} pages</span>
                        <PrivilegeGate privilege="menu.delete">
                          <Button
                            variant="danger"
                            size="sm"
                            onClick={() => confirm(`Delete "${item.title}"?`) && deleteMutation.mutate(item.id)}
                          >
                            Delete
                          </Button>
                        </PrivilegeGate>
                      </li>
                    )}
                  </Draggable>
                ))}
                {provided.placeholder}
                {menuData?.length === 0 && (
                  <li className="px-4 py-8 text-center text-gray-400 text-sm">No menu items yet.</li>
                )}
              </ul>
            )}
          </Droppable>
        </DragDropContext>
      </div>
    </div>
  );
}
