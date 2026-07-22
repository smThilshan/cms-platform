class PageModel {
  final int id;
  final String title;
  final String slug;
  final String body;
  final String status;
  final String? coverImage;
  final String createdAt;

  const PageModel({
    required this.id,
    required this.title,
    required this.slug,
    required this.body,
    required this.status,
    required this.coverImage,
    required this.createdAt,
  });

  factory PageModel.fromJson(Map<String, dynamic> json) => PageModel(
        id: json['id'] as int,
        title: json['title'] as String,
        slug: json['slug'] as String,
        body: json['body'] as String,
        status: json['status'] as String,
        coverImage: json['cover_image'] as String?,
        createdAt: json['created_at'] as String,
      );
}

class MenuItemModel {
  final int id;
  final String title;
  final String slug;
  final List<PageModel> pages;
  final List<MenuItemModel> children;

  const MenuItemModel({
    required this.id,
    required this.title,
    required this.slug,
    required this.pages,
    required this.children,
  });

  factory MenuItemModel.fromJson(Map<String, dynamic> json) => MenuItemModel(
        id: json['id'] as int,
        title: json['title'] as String,
        slug: json['slug'] as String,
        pages: (json['pages'] as List<dynamic>)
            .map((e) => PageModel.fromJson(e as Map<String, dynamic>))
            .toList(),
        children: (json['children'] as List<dynamic>)
            .map((e) => MenuItemModel.fromJson(e as Map<String, dynamic>))
            .toList(),
      );
}
