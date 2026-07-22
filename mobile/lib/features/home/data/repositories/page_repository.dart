import 'package:dio/dio.dart';
import 'package:cms_mobile/features/home/data/models/page_model.dart';

class PageRepository {
  const PageRepository(this._dio);

  final Dio _dio;

  Future<List<PageModel>> getPages() async {
    final response = await _dio.get<Map<String, dynamic>>('/pages');
    final list = response.data!['data'] as List<dynamic>;
    return list
        .map((e) => PageModel.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<PageModel> getPage(String slug) async {
    final response = await _dio.get<Map<String, dynamic>>('/pages/$slug');
    return PageModel.fromJson(response.data!['data'] as Map<String, dynamic>);
  }
}
