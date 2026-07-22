import 'package:dio/dio.dart';
import 'package:cms_mobile/features/home/data/models/page_model.dart';

class PageRepository {
  const PageRepository(this._dio);

  final Dio _dio;

  // Fetches menu and flattens all embedded pages into a single list.
  // The public API has no standalone "list pages" endpoint — pages are
  // surfaced through menu items, which is the intended public contract.
  Future<List<PageModel>> getPages() async {
    final response = await _dio.get<Map<String, dynamic>>('/menu');
    final items = (response.data!['data'] as List<dynamic>)
        .map((e) => MenuItemModel.fromJson(e as Map<String, dynamic>))
        .toList();

    return _flattenPages(items);
  }

  Future<PageModel> getPage(String slug) async {
    final response = await _dio.get<Map<String, dynamic>>('/pages/$slug');
    return PageModel.fromJson(response.data!['data'] as Map<String, dynamic>);
  }

  List<PageModel> _flattenPages(List<MenuItemModel> items) {
    final pages = <PageModel>[];
    for (final item in items) {
      pages.addAll(item.pages);
      if (item.children.isNotEmpty) {
        pages.addAll(_flattenPages(item.children));
      }
    }
    return pages;
  }
}
