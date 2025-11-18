// app/hooks/useProtectedRoute.ts
import { useEffect } from 'react';
import { useRouter, useSegments } from 'expo-router';

export function useProtectedRoute(session: string | null, isLoading: boolean) {
  const segments = useSegments();
  const router = useRouter();

  useEffect(() => {
    if (isLoading) return;

    const inAuthGroup = segments[0] === '(auth)';
    
    // Lista de rotas que qualquer um pode acessar sem estar logado
    const publicRoutes = ['recuperarSenha', 'verificarResposta', 'redefinirSenha'];
    const isPublicRoute = publicRoutes.includes(segments[0] as string);

    // 1. Se NÃO tem sessão, NÃO está no grupo (auth) e NÃO é uma rota pública -> Manda pro Login
    if (!session && !inAuthGroup && !isPublicRoute) {
      router.replace('/(auth)/login');
    } 
    // 2. Se TEM sessão e tenta acessar login ou cadastro -> Manda para a Home (ou Dashboard)
    else if (session && inAuthGroup) {
      router.replace('/(tabs)'); 
    }
  }, [session, isLoading, segments, router]);
}