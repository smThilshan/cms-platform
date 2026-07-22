import type { ButtonHTMLAttributes } from 'react';

interface Props extends ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: 'primary' | 'secondary' | 'danger';
  size?: 'sm' | 'md';
  isLoading?: boolean;
}

const variants = {
  primary: 'bg-indigo-600 text-white hover:bg-indigo-700 disabled:opacity-50',
  secondary: 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50',
  danger: 'bg-red-600 text-white hover:bg-red-700',
};

const sizes = {
  sm: 'px-3 py-1.5 text-sm',
  md: 'px-4 py-2 text-sm',
};

export default function Button({
  variant = 'primary',
  size = 'md',
  isLoading,
  children,
  className = '',
  disabled,
  ...props
}: Props) {
  return (
    <button
      className={`inline-flex items-center gap-2 rounded-md font-medium transition-colors cursor-pointer ${variants[variant]} ${sizes[size]} ${className}`}
      disabled={disabled || isLoading}
      {...props}
    >
      {isLoading && <span className="animate-spin rounded-full h-3.5 w-3.5 border-b-2 border-current" />}
      {children}
    </button>
  );
}
