import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';
import 'package:cms_mobile/features/home/cubit/home_cubit.dart';
import 'package:cms_mobile/features/home/cubit/home_state.dart';
import 'package:cms_mobile/features/home/ui/page_card.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  @override
  void initState() {
    super.initState();
    context.read<HomeCubit>().loadPages();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('CMS Platform')),
      body: BlocBuilder<HomeCubit, HomeState>(
        builder: (context, state) {
          return switch (state) {
            HomeInitial() => const SizedBox.shrink(),
            HomeLoading() => const Center(child: CircularProgressIndicator()),
            HomeError(:final message) => _ErrorView(
                message: message,
                onRetry: () => context.read<HomeCubit>().loadPages(),
              ),
            HomeLoaded(:final pages) when pages.isEmpty => const _EmptyView(),
            HomeLoaded(:final pages) => RefreshIndicator(
                onRefresh: () => context.read<HomeCubit>().loadPages(),
                child: ListView.separated(
                  padding: const EdgeInsets.all(16),
                  itemCount: pages.length,
                  separatorBuilder: (_, _) => const SizedBox(height: 12),
                  itemBuilder: (context, index) => PageCard(
                    page: pages[index],
                    onTap: () => context.push('/page/${pages[index].slug}'),
                  ),
                ),
              ),
          };
        },
      ),
    );
  }
}

class _ErrorView extends StatelessWidget {
  const _ErrorView({required this.message, required this.onRetry});

  final String message;
  final VoidCallback onRetry;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Icon(Icons.wifi_off_rounded, size: 48, color: Colors.grey),
            const SizedBox(height: 16),
            Text(message,
                textAlign: TextAlign.center,
                style: const TextStyle(color: Colors.grey)),
            const SizedBox(height: 16),
            FilledButton(onPressed: onRetry, child: const Text('Retry')),
          ],
        ),
      ),
    );
  }
}

class _EmptyView extends StatelessWidget {
  const _EmptyView();

  @override
  Widget build(BuildContext context) {
    return const Center(
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(Icons.inbox_outlined, size: 48, color: Colors.grey),
          SizedBox(height: 16),
          Text('No published pages yet.',
              style: TextStyle(color: Colors.grey)),
        ],
      ),
    );
  }
}
