import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_html/flutter_html.dart';
import 'package:cms_mobile/core/theme/app_theme.dart';
import 'package:cms_mobile/features/page_detail/cubit/page_detail_cubit.dart';
import 'package:cms_mobile/features/page_detail/cubit/page_detail_state.dart';
import 'package:cms_mobile/features/home/data/models/page_model.dart';

class PageDetailScreen extends StatefulWidget {
  const PageDetailScreen({super.key, required this.slug});

  final String slug;

  @override
  State<PageDetailScreen> createState() => _PageDetailScreenState();
}

class _PageDetailScreenState extends State<PageDetailScreen> {
  @override
  void initState() {
    super.initState();
    context.read<PageDetailCubit>().loadPage(widget.slug);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        leading: const BackButton(),
        title: const Text(''),
      ),
      body: BlocBuilder<PageDetailCubit, PageDetailState>(
        builder: (context, state) {
          return switch (state) {
            PageDetailInitial() => const SizedBox.shrink(),
            PageDetailLoading() =>
              const Center(child: CircularProgressIndicator()),
            PageDetailError(:final message) => _ErrorView(
                message: message,
                onRetry: () =>
                    context.read<PageDetailCubit>().loadPage(widget.slug),
              ),
            PageDetailLoaded(:final page) => _PageBody(page: page),
          };
        },
      ),
    );
  }
}

class _PageBody extends StatelessWidget {
  const _PageBody({required this.page});

  final PageModel page;

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (page.coverImage != null)
            CachedNetworkImage(
              imageUrl: page.coverImage!,
              width: double.infinity,
              height: 240,
              fit: BoxFit.cover,
              placeholder: (_, _) => Container(
                height: 240,
                color: AppTheme.surface,
                child: const Center(child: CircularProgressIndicator()),
              ),
              errorWidget: (_, _, _) => const SizedBox.shrink(),
            ),
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 20, 16, 8),
            child: Text(
              page.title,
              style: const TextStyle(
                fontSize: 24,
                fontWeight: FontWeight.w800,
                color: AppTheme.textPrimary,
                height: 1.3,
              ),
            ),
          ),
          const Divider(indent: 16, endIndent: 16),
          Html(
            data: page.body,
            style: {
              'body': Style(
                margin: Margins.symmetric(horizontal: 16),
                fontSize: FontSize(15),
                lineHeight: const LineHeight(1.7),
                color: AppTheme.textPrimary,
              ),
              'h1': Style(fontWeight: FontWeight.w700),
              'h2': Style(fontWeight: FontWeight.w700),
              'h3': Style(fontWeight: FontWeight.w600),
              'a': Style(color: AppTheme.primary),
            },
          ),
          const SizedBox(height: 32),
        ],
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
