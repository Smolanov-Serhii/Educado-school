"""Read-only HTTP checks for the six landing pages and supplied crawler files."""
from concurrent.futures import ThreadPoolExecutor
from html.parser import HTMLParser
from pathlib import Path
from urllib.request import Request, urlopen
import json
import re
import argparse

ROOT = Path(__file__).resolve().parents[1]
BASE_URL = 'https://educado.school'
LANGUAGES = {'anhliyska': 'англійської', 'ispanska': 'іспанської', 'frantsuzka': 'французької', 'nimetska': 'німецької', 'polska': 'польської', 'italiyska': 'італійської'}

class Headings(HTMLParser):
    def __init__(self):
        super().__init__()
        self.items = []
        self.current = None
    def handle_starttag(self, tag, attrs):
        if tag in ('h1', 'h2', 'h3', 'h4', 'h5', 'h6'):
            assert self.current is None, 'Nested heading'
            self.current = [tag, '']
    def handle_data(self, data):
        if self.current is not None:
            self.current[1] += data
    def handle_endtag(self, tag):
        if self.current is not None and self.current[0] == tag:
            self.current[1] = ' '.join(self.current[1].split())
            self.items.append(self.current)
            self.current = None

def fetch(path):
    with urlopen(Request(BASE_URL.rstrip('/') + '/' + path, headers={'User-Agent': 'Educado-SEO-Verification/1.0'}), timeout=45) as response:
        return response.status, response.read().decode('utf-8-sig'), response.headers.get('Content-Type')

def check_page(slug):
    status, html, _ = fetch(slug + '/')
    parser = Headings()
    parser.feed(html)
    headings = parser.items
    assert status == 200 and sum(tag == 'h1' for tag, _ in headings) == 1, slug
    previous = 0
    for tag, text in headings:
        level = int(tag[1])
        assert level <= previous + 1, (slug, 'level skip', text)
        previous = level
    g = LANGUAGES[slug]
    required = [
        ['h1', f'Курси {g} мови онлайн в EDUCADO'],
        ['h2', 'Хочеш подивитися, як проходить навчання?'],
        ['h2', f'Як почати навчання {g} мови в EDUCADO'],
        ['h2', f'Викладачі {g} мови'],
        ['h2', f'Відгуки про курси {g} мови'],
        ['h2', f'Часті запитання про курси {g} мови'],
        ['h2', 'Залишилися питання? Залиш заявку — ми зв’яжемося з тобою'],
    ]
    for item in required:
        assert item in headings, (slug, 'missing', item)
    assert html.count('class="faq-item__header"') == 7
    assert sum(tag == 'h3' and text.endswith('?') for tag, text in headings) == 7
    if slug in ('anhliyska', 'ispanska'):
        assert ['h2', f'Курси {g} мови під вашу мету'] in headings
        assert sum(tag == 'h3' for tag, _ in headings) == (14 if slug == 'anhliyska' else 11)
    else:
        assert ['h2', f'Переваги курсів {g} мови онлайн'] in headings
        assert ['h3', f'Ціни на курси {g} мови'] in headings
        assert re.search(r'<ul[^>]*class="whyus-list"', html)
    beginner = False  # Copywriter text is pending; no empty section should render.
    assert ('class="ed-home-language-beginners"' in html) == beginner
    assert 'Андглійська' not in html
    assert 'Fatal error' not in html
    return {'slug': slug, 'status': status, 'headings': headings}

if __name__ == '__main__':
    arguments = argparse.ArgumentParser()
    arguments.add_argument('--base-url', default=BASE_URL)
    arguments.add_argument('--report', default='docs/seo-verification.json')
    options = arguments.parse_args()
    BASE_URL = options.base_url
    with ThreadPoolExecutor(max_workers=3) as pool:
        report = list(pool.map(check_page, LANGUAGES))
    for filename in ('robots.txt', 'llms.txt'):
        status, text, content_type = fetch(filename)
        assert status == 200 and 'text/plain' in content_type
        assert text == (ROOT / filename).read_text(encoding='utf-8-sig'), filename
        report.append({'file': filename, 'status': status, 'matches_spec': True})
    (ROOT / options.report).write_text(json.dumps({'base_url': BASE_URL, 'checks': report}, ensure_ascii=False, indent=2), encoding='utf-8')
    print('PASS: six heading outlines; seven FAQ questions each; beginner sections hidden pending copy; robots.txt and llms.txt match supplied files.')
