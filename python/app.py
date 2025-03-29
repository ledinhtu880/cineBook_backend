import json
import requests
import sys
import json
import os
from bs4 import BeautifulSoup # type: ignore
from datetime import datetime, timedelta
import re

sys.stdout.reconfigure(encoding='utf-8')

urls = {
    "now_showing": "https://moveek.com/dang-chieu/",
    "coming_soon": "https://moveek.com/sap-chieu/"
}

headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36"
}

def get_movie_details(movie_url):
    response = requests.get(movie_url, headers=headers)
    if response.status_code != 200:
        return {
            "description": "Chưa có mô tả",
            "duration": None,
            "trailer_url": None,
            "age_rating": "P",
            "genres": []
        }

    soup = BeautifulSoup(response.text, "lxml")

    # Mô tả phim
    description_tag = soup.find("p", class_="mb-3 text-justify")
    description = description_tag.get_text(strip=True) if description_tag else "Chưa có mô tả"

    # Lấy thể loại phim
    genre_tag = soup.find("p", class_="mb-0 text-muted text-truncate")
    genres = []
    if genre_tag:
        genre_text = genre_tag.get_text(strip=True)
        if "-" in genre_text:
            genres = [g.strip() for g in genre_text.split("-")[1].split(",")]

    # Trailer URL
    trailer_tag = soup.find("a", {"data-video-url": True})
    trailer_url = f"https://www.youtube.com/watch?v={trailer_tag['data-video-url']}" if trailer_tag else None

    # Lấy thời lượng
    duration = None
    duration_tag = soup.find("span", string=lambda s: s and "phút" in s)
    if duration_tag:
        match = re.search(r"(\d+)", duration_tag.get_text(strip=True))
        if match:
            duration = int(match.group(1))

    # Lấy giới hạn tuổi (sửa lỗi)
    age_rating = "P"
    
    # Tìm tất cả các div column
    col_divs = soup.find_all("div", class_="col text-center text-sm-left")
    
    for div in col_divs:
        # Tìm strong tag trong div
        strong_tag = div.find("strong")
        if strong_tag:
            # Tìm span trong strong
            span_in_strong = strong_tag.find("span", class_="d-none d-sm-inline-block")
            if span_in_strong and "Giới hạn tuổi" in span_in_strong.get_text(strip=True):
                # Lấy span ngay sau thẻ <br>
                br_tag = div.find("br")
                if br_tag and br_tag.next_sibling and hasattr(br_tag.next_sibling, 'name') and br_tag.next_sibling.name == "span":
                    age_rating = br_tag.next_sibling.get_text(strip=True)
                    break
                # Nếu không tìm được span sau br, tìm span bất kỳ trong div
                else:
                    span_tags = div.find_all("span")
                    if len(span_tags) > 1:  # Nếu có nhiều hơn 1 span (span đầu tiên là "Giới hạn tuổi")
                        age_rating = span_tags[-1].get_text(strip=True)  # Lấy span cuối cùng
                        break
                    
    full_release_date = None
    release_date_divs = soup.find_all("div", class_="col text-center text-sm-left")
    for div in release_date_divs:
        strong = div.find("strong")
        if strong and strong.find("span", class_="d-none d-sm-inline-block", string="Khởi chiếu"):
            span = div.find("span", recursive=False)  # Tìm span trực tiếp trong div
            if span:
                date_text = span.get_text(strip=True)
                # Kiểm tra xem có định dạng ngày/tháng/năm không
                if re.match(r'\d{2}/\d{2}/\d{4}', date_text):
                    full_release_date = date_text
                    break

    return {
        "description": description,
        "duration": duration,
        "trailer_url": trailer_url,
        "age_rating": age_rating,
        "genres": genres,
        "full_release_date": full_release_date
    }


def get_movies(url, filter_release_date=False):
    response = requests.get(url, headers=headers)
    if response.status_code != 200:
        print(json.dumps({"error": f"Không thể lấy dữ liệu từ {url}. Mã lỗi: {response.status_code}"}))
        return []

    soup = BeautifulSoup(response.text, "lxml")
    movies = soup.find_all("div", class_="item")
    movie_list = []

    for movie in movies:
        title_tag = movie.find("h3")
        title = title_tag.get_text(strip=True) if title_tag else "Không có tiêu đề"

        img_tag = movie.find("img")
        poster_url = img_tag["data-src"] if img_tag and "data-src" in img_tag.attrs else "Không có poster"

        if "no-poster.png" in poster_url:
            continue

        release_date_tag = movie.find("div", class_="col text-muted")
        release_date_str = release_date_tag.get_text(strip=True) if release_date_tag else "Không có ngày phát hành"

        # Thêm năm vào ngày phát hành
        current_year = datetime.now().year
        formatted_release_date = release_date_str
        
        try:
            if release_date_str and "/" in release_date_str:
                release_date = datetime.strptime(release_date_str, "%d/%m")
                release_date = release_date.replace(year=current_year)
                
                # Nếu tháng phát hành < tháng hiện tại và chúng ta đang ở Q3/Q4, có thể đây là phim năm sau
                if release_date.month < datetime.now().month and datetime.now().month > 9:
                    release_date = release_date.replace(year=current_year + 1)
                
                formatted_release_date = release_date.strftime("%d/%m/%Y")
        except ValueError:
            formatted_release_date = "Không xác định"

        if filter_release_date:
            try:
                release_date = datetime.strptime(formatted_release_date, "%d/%m/%Y")
                if release_date > datetime.now() + timedelta(days=14):
                    continue
            except ValueError:
                continue

        movie_link_tag = title_tag.find("a") if title_tag else None
        movie_url = "https://moveek.com" + movie_link_tag["href"] if movie_link_tag else None

        movie_details = get_movie_details(movie_url) if movie_url else {}

        movie_list.append({
            "title": title,
            "poster_url": poster_url,
            "release_date": formatted_release_date,
            **movie_details
        })

    return movie_list

now_showing_movies = get_movies(urls["now_showing"])
coming_soon_movies = get_movies(urls["coming_soon"], filter_release_date=True)

all_movies = {
    "now_showing": now_showing_movies,
    "coming_soon": coming_soon_movies
}

script_dir = os.path.dirname(os.path.abspath(__file__))
json_path = os.path.join(script_dir, "movies.json")

# Lưu dữ liệu vào JSON trong cùng thư mục với app.py
with open(json_path, "w", encoding="utf-8") as f:
    json.dump(all_movies, f, ensure_ascii=False, indent=4)

print("Dữ liệu phim đã được lưu vào movies.json")