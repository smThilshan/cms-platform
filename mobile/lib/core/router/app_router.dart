import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import 'package:cms_mobile/features/home/data/repositories/page_repository.dart';
import 'package:cms_mobile/features/home/ui/home_screen.dart';
import 'package:cms_mobile/features/page_detail/cubit/page_detail_cubit.dart';
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
        // Provide a fresh PageDetailCubit per navigation so stale content
        // from the previous page never flashes when the user opens another page.
        builder: (context, state) {
          final slug = state.pathParameters['slug']!;
          final repository = context.read<PageRepository>();
          return BlocProvider(
            create: (_) => PageDetailCubit(repository),
            child: PageDetailScreen(slug: slug),
          );
        },
      ),
    ],
  );
}
