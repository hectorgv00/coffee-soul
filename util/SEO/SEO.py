import requests
from bs4 import BeautifulSoup
from urllib.parse import urlparse

def is_external_link(href, domain):
    if href is None:
        return False
    parsed = urlparse(href)
    # An external link: scheme is http/https and netloc differs from the main domain
    return (parsed.scheme in ["http", "https"]) and (parsed.netloc and parsed.netloc != domain)

def main():
    url = input("Enter the URL: ").strip()
    if not url.startswith("http"):
        url = "http://" + url
    
    # Fetch the page
    try:
        response = requests.get(url, timeout=10)
    except Exception as e:
        print(f"[ERROR] Failed to retrieve page: {e}")
        return
    
    if response.status_code != 200:
        print(f"[ERROR] Failed to retrieve page. Status code: {response.status_code}")
        return
    
    html_content = response.text
    soup = BeautifulSoup(html_content, "html.parser")
    
    domain = urlparse(url).netloc

    # ---------------------------------------
    # 1. HTML <lang> attribute
    html_tag = soup.find("html")
    if html_tag and html_tag.get("lang"):
        print("[OK] <html> has a lang attribute.")
    else:
        print("[WARNING] <html> tag missing a lang attribute. Consider adding it for better accessibility and SEO.")

    # 2. Exactly one <h1> tag
    h1_tags = soup.find_all("h1")
    if len(h1_tags) == 1:
        print("[OK] Exactly one <h1> tag found.")
    elif len(h1_tags) == 0:
        print("[WARNING] No <h1> tag found.")
    else:
        print(f"[WARNING] Multiple <h1> tags found ({len(h1_tags)}). Consider using only one.")

    # 3. Check for at least one <h2> tag
    h2_tags = soup.find_all("h2")
    if len(h2_tags) > 0:
        print("[OK] <h2> tags found. Good for subheading structure.")
    else:
        print("[INFO] No <h2> tags found. Consider using them for better content hierarchy.")

    # 4. <title> tag presence and length
    title_tag = soup.find("title")
    if title_tag:
        title_text = title_tag.get_text(strip=True)
        if title_text:
            print("[OK] <title> tag is present and not empty.")
            if len(title_text) < 30 or len(title_text) > 65:
                print(f"[INFO] <title> length ({len(title_text)} chars) may not be optimal. Ideally ~50-60 chars.")
        else:
            print("[WARNING] <title> tag is present but empty.")
    else:
        print("[WARNING] Missing <title> tag.")

    # 5. Meta description
    meta_desc = soup.find("meta", attrs={"name": "description"})
    if meta_desc and meta_desc.get("content", "").strip():
        desc_content = meta_desc.get("content", "").strip()
        print("[OK] Meta description found.")
        if len(desc_content) < 50 or len(desc_content) > 160:
            print(f"[INFO] Meta description length ({len(desc_content)} chars) may not be optimal (50-160 chars recommended).")
    else:
        print("[WARNING] Meta description missing or empty.")

    # 6. Meta viewport (for mobile-friendliness)
    meta_viewport = soup.find("meta", attrs={"name": "viewport"})
    if meta_viewport and meta_viewport.get("content", ""):
        print("[OK] Viewport meta tag found.")
    else:
        print("[INFO] No viewport meta tag found. Consider adding for mobile-friendly pages.")

    # 7. Canonical link
    canonical_link = soup.find("link", rel="canonical")
    if canonical_link and canonical_link.get("href"):
        print("[OK] Canonical link found.")
    else:
        print("[INFO] No canonical link found. Consider adding one.")

    # 8. Robots meta tag
    meta_robots = soup.find("meta", attrs={"name": "robots"})
    if meta_robots and meta_robots.get("content"):
        print("[OK] Robots meta tag found.")
    else:
        print("[INFO] No robots meta tag found. This is optional but can control indexing.")

    # 9. Check for <nav> element (semantic structure)
    if soup.find("nav"):
        print("[OK] <nav> element found for site navigation.")
    else:
        print("[INFO] No <nav> element found. Consider using semantic <nav> for main navigation.")

    # 10. All images have alt attributes
    imgs = soup.find_all("img")
    images_without_alt = [img for img in imgs if not img.get("alt")]
    if images_without_alt:
        print(f"[WARNING] {len(images_without_alt)} image(s) without alt attributes.")
    else:
        print("[OK] All images have alt attributes.")

    # 11. Basic HTML5 semantic tags: <header>, <main>, <footer>
    semantic_tags = ["header", "main", "footer"]
    missing_semantic = [tag for tag in semantic_tags if not soup.find(tag)]
    if missing_semantic:
        print("[INFO] Missing semantic tags: " + ", ".join(missing_semantic))
    else:
        print("[OK] Basic semantic tags (<header>, <main>, <footer>) present.")

    # 12. Structured data (JSON-LD)
    ld_json_scripts = soup.find_all("script", type="application/ld+json")
    if ld_json_scripts:
        print("[OK] Structured data (JSON-LD) found.")
    else:
        print("[INFO] No structured data (JSON-LD) found.")

    # 13. Anchor text checks for generic text
    generic_texts = ["click here", "haz clic aquí", "here", "aquí"]
    a_tags = soup.find_all("a")
    anchor_warnings = []
    for a in a_tags:
        text = (a.get_text() or "").strip().lower()
        if any(g in text for g in generic_texts):
            anchor_warnings.append(text)
    if anchor_warnings:
        print(f"[WARNING] Found {len(anchor_warnings)} anchor(s) with non-descriptive text (e.g. 'click here').")
    else:
        print("[OK] No generic 'click here' type anchor text found.")

    # 14. External links should have rel="noopener" or rel="noreferrer"
    external_links = [a for a in a_tags if is_external_link(a.get("href"), domain)]
    external_links_missing_rel = []
    for a in external_links:
        rel = a.get("rel", [])
        if isinstance(rel, str):
            rel = [rel]
        rel_lower = [r.lower() for r in rel]
        if "noopener" not in rel_lower and "noreferrer" not in rel_lower:
            external_links_missing_rel.append(a.get("href"))
    if external_links_missing_rel:
        print(f"[WARNING] External links without rel=\"noopener\" or \"noreferrer\":")
        for link in external_links_missing_rel:
            print(" -", link)
    else:
        print("[OK] All external links have appropriate rel attributes.")

    # 15. Open Graph tags
    og_tags = soup.find_all("meta", property=lambda x: x and x.startswith("og:"))
    if og_tags:
        print("[OK] Open Graph tags found (good for social media sharing).")
    else:
        print("[INFO] No Open Graph tags found.")

    # 16. Twitter Card tags
    twitter_tags = soup.find_all("meta", attrs={"name": lambda x: x and x.startswith("twitter:")})
    if twitter_tags:
        print("[OK] Twitter Card tags found (good for social sharing on Twitter).")
    else:
        print("[INFO] No Twitter Card tags found.")

    # 17. Count external links as a metric (just informational)
    print(f"[INFO] Found {len(external_links)} external link(s) on the page.")
    
    # 18. Checking internal linking structure (just a basic check)
    # If there's at least one internal link (not required, but good for SEO)
    internal_links = [a for a in a_tags if not is_external_link(a.get("href"), domain) and a.get("href")]
    if internal_links:
        print("[OK] Internal links found, helping crawlers navigate.")
    else:
        print("[INFO] No internal links found. Consider adding internal navigation.")

    print("\nValidation complete.")

if __name__ == "__main__":
    main()
