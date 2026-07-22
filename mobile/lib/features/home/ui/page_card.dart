import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:cms_mobile/core/theme/app_theme.dart';
import 'package:cms_mobile/features/home/data/models/page_model.dart';

class PageCard extends StatelessWidget {
  const PageCard({super.key, required this.page, required this.onTap});

  final PageModel page;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return Card(
      clipBehavior: Clip.antiAlias,
      child: InkWell(
        onTap: onTap,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (page.coverImage != null)
              CachedNetworkImage(
                imageUrl: page.coverImage!,
                height: 180,
                width: double.infinity,
                fit: BoxFit.cover,
                placeholder: (_, _) => Container(
                  height: 180,
                  color: AppTheme.surface,
                  child: const Center(
                    child: CircularProgressIndicator(strokeWidth: 2),
                  ),
                ),
                errorWidget: (_, _, _) => Container(
                  height: 180,
                  color: AppTheme.surface,
                  child: const Icon(Icons.broken_image_outlined,
                      color: AppTheme.textSecondary),
                ),
              )
            else
              Container(
                height: 120,
                color: AppTheme.surface,
                child: const Center(
                  child: Icon(Icons.article_outlined,
                      size: 40, color: AppTheme.textSecondary),
                ),
              ),
            Padding(
              padding: const EdgeInsets.all(16),
              child: Text(
                page.title,
                style: const TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.w600,
                  color: AppTheme.textPrimary,
                ),
                maxLines: 2,
                overflow: TextOverflow.ellipsis,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
