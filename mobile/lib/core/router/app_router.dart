import 'package:go_router/go_router.dart';
import 'package:cms_mobile/features/home/ui/home_screen.dart';
import 'package:cms_mobile/features/page_detail/ui/page_detail_screen.dart';

class AppRouter {
  AppRouter._();

  static final GoRouter router = GoRouter(
    initialLocation: '/',
    routes: [
      GoRoute(
        path: '/',
        builder: (context, state) => const HomeScreen(),
      ),
      GoRoute(
        path: '/page/:slug',
        builder: (context, state) {
          final slug = state.pathParameters['slug']!;
          return PageDetailScreen(slug: slug);
        },
      ),
    ],
  );
}
