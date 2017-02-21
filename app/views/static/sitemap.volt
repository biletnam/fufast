<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:geo="http://www.google.com/geo/schemas/sitemap/1.0" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0" xmlns:pagemap="http://www.google.com/schemas/sitemap-pagemap/1.0" xmlns:xhtml="http://www.w3.org/1999/xhtml">
  <url>
    <loc>http://fufayka.info</loc>
  </url>

  {% for section in tree.getAll() %}
    <url>
      <loc>http://fufayka.info{{ url('catalog/' ~ section['CODE']|lower) }}</loc>
    </url> 
  {% endfor %}

  {% for product in products %}
    <url>
      <loc>http://fufayka.info{{ url('catalog/' ~ product['ID']) }}</loc>
    </url> 
  {% endfor %}  
</urlset>