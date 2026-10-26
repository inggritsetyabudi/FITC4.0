import React, { createContext, useContext, useState, useEffect } from 'react';
import type { User, UserRole } from '../types';
import { authService } from '../services/api';
import { INITIAL_USERS } from '../utils/mockData';

interface AuthContextType {
  user: User | null;
  login: (username: string, password?: string) => Promise<boolean>;
  logout: () => void;
  switchRole: (role: UserRole) => void;
}

const AuthContext = createContext<AuthContextType>({
  user: null,
  login: async () => false,
  logout: () => {},
  switchRole: () => {}
});

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<User | null>(() => authService.getCurrentUser());

  useEffect(() => {
    if (!user) {
      setUser(INITIAL_USERS[0]);
    }
  }, []);

  const login = async (username: string, password?: string): Promise<boolean> => {
    const res = await authService.login(username, password);
    if (res.success && res.user) {
      setUser(res.user);
      return true;
    }
    return false;
  };

  const logout = () => {
    authService.logout();
    setUser(null);
  };

  const switchRole = (role: UserRole) => {
    const found = INITIAL_USERS.find(u => u.role === role) || INITIAL_USERS[0];
    localStorage.setItem('posyandu_pintar_currentUser', JSON.stringify(found));
    setUser(found);
  };

  return (
    <AuthContext.Provider value={{ user, login, logout, switchRole }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);
