import { useAuth } from '../contexts/AuthContext';

interface Props {
  privilege: string;
  children: React.ReactNode;
  fallback?: React.ReactNode;
}

export default function PrivilegeGate({ privilege, children, fallback = null }: Props) {
  const { hasPrivilege } = useAuth();
  return hasPrivilege(privilege) ? <>{children}</> : <>{fallback}</>;
}
