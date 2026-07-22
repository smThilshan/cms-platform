export interface Privilege {
  id: number;
  key: string;
  description: string;
}

export interface Role {
  id: number;
  name: string;
  slug: string;
  privileges: Privilege[];
}

export interface User {
  id: number;
  name: string;
  email: string;
  role: Role;
  privileges: string[];
}

export interface Page {
  id: number;
  title: string;
  slug: string;
  body: string;
  status: 'draft' | 'published';
  cover_image: string | null;
  menu_item: { id: number; title: string; slug: string } | null;
  created_at: string;
  updated_at: string;
}

export interface MenuItem {
  id: number;
  title: string;
  slug: string;
  order: number;
  parent_id: number | null;
  children: MenuItem[];
  pages: Page[];
}

export interface PaginatedResponse<T> {
  data: T[];
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
  links: {
    first: string;
    last: string;
    prev: string | null;
    next: string | null;
  };
}
