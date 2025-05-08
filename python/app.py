from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager
import time
import json
import sys
import os

sys.stdout.reconfigure(encoding='utf-8')

def get_movie_details(driver, detail_url):
    """Lấy thông tin chi tiết của phim từ trang chi tiết"""
    driver.get(detail_url)
    time.sleep(10)  # Đợi trang tải
    
    details = {}
    
    try:
        # Lấy backdrop Image (Image có alt là "Img Movie")
        try:
            backdrop_img = driver.find_element(By.CSS_SELECTOR, "img[alt='Img Movie']")
            details["backdrop_url"] = backdrop_img.get_attribute("src")
        except Exception as e:
            print(f"Không tìm thấy backdrop: {e}")
            details["backdrop_url"] = None
        
        # Tên phim chính thức
        try:
            title_elem = driver.find_element(By.CSS_SELECTOR, "div.item__title.flex.items-center h1")
            details["official_title"] = title_elem.text
        except Exception as e:
            print(f"Không tìm thấy tên phim: {e}")
        
        # Thời lượng
        try:
            # Tìm tất cả div chứa SVG và span
            time_divs = driver.find_elements(By.CSS_SELECTOR, "div.text-sm.flex.items-center.font-semibold.not-italic")
            
            for div in time_divs:
                # Kiểm tra có SVG đồng hồ không (path có "M7 0C3.13306")
                svg_paths = div.find_elements(By.TAG_NAME, "path")
                for path in svg_paths:
                    if "M7 0C3.13306" in path.get_attribute("d"):
                        # Nếu tìm thấy SVG đồng hồ, lấy text từ span
                        duration = div.find_element(By.TAG_NAME, "span").text
                        details["duration"] = duration
                        break
        except Exception as e:
            print(f"Không tìm thấy thời lượng theo cách mới: {e}")
        
        # Ngày khởi chiếu
        try:
            # Tìm div chứa text ngày
            release_divs = driver.find_elements(By.CSS_SELECTOR, "div.text-sm.flex.items-center.font-semibold.not-italic")
            
            for div in release_divs:
                # Kiểm tra có SVG lịch không (path có "M10.7143")
                svg_paths = div.find_elements(By.TAG_NAME, "path")
                for path in svg_paths:
                    if "M10.7143" in path.get_attribute("d"):
                        # Nếu tìm thấy SVG lịch, lấy text từ span
                        release_date = div.find_element(By.TAG_NAME, "span").text
                        details["release_date"] = release_date
                        break
        except Exception as e:
            print(f"Không tìm thấy ngày khởi chiếu theo cách mới: {e}")
        
        # Quốc gia
        try:
            country_elems = driver.find_elements(By.CSS_SELECTOR, "div.flex.flex-nowrap.text-sm")
            for elem in country_elems:
                if "Quốc gia:" in elem.text:
                    country = elem.find_element(By.CSS_SELECTOR, "span:last-child").text
                    details["country"] = country
                    break
        except Exception as e:
            print(f"Không tìm thấy quốc gia: {e}")
        
        # Thể loại
        try:
            genre_links = driver.find_elements(By.CSS_SELECTOR, "div.flex.flex-nowrap.items-center a.text-black.inline-flex.h-8.border")
            if genre_links:
                genres = [link.text.strip() for link in genre_links if link.text.strip() and "/dien-anh/" in link.get_attribute("href")]
                details["genres"] = genres
        except Exception as e:
            print(f"Không tìm thấy thể loại: {e}")
        
        # Nội dung phim / Mô tả
        try:
            description_elem = driver.find_element(By.CSS_SELECTOR, "div.block__wysiwyg")
            details["description"] = description_elem.text
        except Exception as e:
            try:
                description_elem = driver.find_element(By.CSS_SELECTOR, "div.content__data__full")
                details["description"] = description_elem.text
            except Exception as e:
                print(f"Không tìm thấy mô tả phim: {e}")
                details["description"] = "Không có mô tả"
        
    except Exception as e:
        print(f"Lỗi khi xử lý trang chi tiết: {e}")
    
    return details

def get_trailer_url(movie_title):
    """Tìm trailer URL cho một phim cụ thể"""
    try:
        # Tìm phim trên YouTube
        search_term = f"{movie_title} trailer"
        search_url = f"https://www.youtube.com/results?search_query={search_term.replace(' ', '+')}"
        
        # Tạo một driver mới để không ảnh hưởng đến quá trình chính
        trailer_options = Options()
        trailer_options.add_argument("--headless")
        trailer_driver = webdriver.Chrome(
            service=Service(ChromeDriverManager().install()),
            options=trailer_options
        )
        
        trailer_driver.get(search_url)
        time.sleep(3)
        
        # Lấy video đầu tiên
        video = trailer_driver.find_element(By.CSS_SELECTOR, "ytd-video-renderer a#video-title")
        video_id = video.get_attribute("href").split("v=")[1].split("&")[0]
        trailer_url = f"https://www.youtube.com/embed/{video_id}"
        
        trailer_driver.quit()
        return trailer_url
    except Exception as e:
        print(f"Không thể tìm trailer cho {movie_title}: {e}")
        return None

def get_galaxy_movies_selenium(url):
    # Thiết lập trình duyệt Chrome
    options = Options()
    options.add_argument("--headless")  # Chạy ngầm không mở trình duyệt
    options.add_argument("--disable-gpu")
    options.add_argument("--no-sandbox")
    options.add_argument("--disable-dev-shm-usage")
    options.add_argument("--window-size=1920,1080")  # Đặt kích thước cửa sổ lớn hơn
    
    driver = webdriver.Chrome(
        service=Service(ChromeDriverManager().install()),
        options=options
    )    
    # Tải trang
    driver.get(url)
    time.sleep(8)  # Đợi trang tải xong
    
    # Tìm các thẻ phim
    movie_cards = driver.find_elements(By.CSS_SELECTOR, "div.Card_card__wrapper__RUTBs")
    
    movie_list = []
    for index, card in enumerate(movie_cards):  
        if(index > 5):
            continue

        try:
            print(f"Đang xử lý phim {index+1}/{len(movie_cards)}")
            
            # Tên phim
            title = None
            try:
                title_elem = card.find_element(By.CSS_SELECTOR, "div.Card_card__title__kFoFc h3")
                title = title_elem.text
            except:
                title = f"Không rõ tiêu đề {index+1}"
            
            # Poster
            poster_url = None
            detail_url = None
            try:
                poster_img = card.find_element(By.CSS_SELECTOR, "img.img__film")
                poster_url = poster_img.get_attribute("src")
                # Lấy alt text của poster nếu có
                alt_text = poster_img.get_attribute("alt")
                
                if alt_text:
                    base_url = "https://www.galaxycine.vn/dat-ve"
                    detail_url = f"{base_url}/{alt_text}/"
            except:
                pass
                
            # Giới hạn độ tuổi
            age_rating = "P"
            try:
                age_span = card.find_element(By.CSS_SELECTOR, "div.age__limit span")
                age_rating = age_span.text
            except:
                pass
                
            # Điểm đánh giá
            rating = None
            try:
                # Tìm div.votes -> p -> span.text-[18px]
                votes_div = card.find_element(By.CSS_SELECTOR, "div.votes")
                if votes_div:
                    rating_span = votes_div.find_element(By.CSS_SELECTOR, "span.text-\\[18px\\]")
                    rating = rating_span.text.strip() if rating_span else None
            except Exception as e:
                print(f"Không tìm thấy rating theo cách 1: {e}")
                try:
                    # Cách thứ hai - tìm trực tiếp dựa vào class
                    rating_span = card.find_element(By.CSS_SELECTOR, "p span.text-\\[18px\\].font-bold.text-white")
                    rating = rating_span.text.strip()
                except Exception as e2:
                    print(f"Không tìm thấy rating theo cách 2: {e2}")
                    try:
                        # Cách thứ ba - tìm tất cả span và lọc
                        spans = card.find_elements(By.TAG_NAME, "span")
                        for span in spans:
                            try:
                                if "font-bold" in span.get_attribute("class") and "text-white" in span.get_attribute("class"):
                                    potential_rating = span.text.strip()
                                    # Kiểm tra xem có phải là số không
                                    if "." in potential_rating and potential_rating.replace(".", "", 1).isdigit():
                                        rating = potential_rating
                                        break
                            except:
                                continue
                    except:
                        pass
                
            # Có trailer hay không
            has_trailer = False
            try:
                trailer_elems = card.find_elements(By.XPATH, ".//button[contains(., 'Trailer')]")
                has_trailer = len(trailer_elems) > 0
            except:
                pass
            
            movie_data = {
                "title": title,
                "poster_url": poster_url,
                "age_rating": age_rating,
                "rating": rating,
                "has_trailer": has_trailer,
                "detail_url": detail_url
            }

            # Nếu phim có trailer, tìm URL trailer
            if has_trailer:
                try:
                    # Sử dụng hàm get_trailer_url với title
                    movie_data["trailer_url"] = get_trailer_url(title)
                except Exception as e:
                    print(f"Lỗi khi tìm trailer: {e}")
                    movie_data["trailer_url"] = None
            

            # Nếu có URL chi tiết, truy cập để lấy thêm thông tin
            if detail_url:
                try:
                    # Tạo một driver mới cho mỗi chi tiết phim
                    detail_options = Options()
                    detail_options.add_argument("--headless")
                    detail_options.add_argument("--disable-gpu")
                    detail_options.add_argument("--no-sandbox")
                    detail_options.add_argument("--disable-dev-shm-usage")
                    
                    detail_driver = webdriver.Chrome(
                        service=Service(ChromeDriverManager().install()),
                        options=detail_options
                    )
                    
                    # Lấy chi tiết phim
                    try:
                        additional_details = get_movie_details(detail_driver, detail_url)
                        movie_data.update(additional_details)
                    finally:
                        # Đảm bảo đóng detail_driver ngay cả khi có lỗi
                        detail_driver.quit()
                        
                except Exception as e:
                    print(f"Lỗi khi lấy thông tin chi tiết: {e}")
            
            movie_list.append(movie_data)
            print(f"Đã xử lý phim: {title}")
        except Exception as e:
            print(f"Lỗi khi xử lý phim: {e}")
    
    driver.quit()
    return movie_list

try:
    # Chỉ lấy phim đang chiếu để tiết kiệm thời gian (bạn có thể bỏ comment để lấy cả phim sắp chiếu)
    print("Đang lấy phim đang chiếu...")
    galaxy_now_showing = get_galaxy_movies_selenium("https://www.galaxycine.vn/phim-dang-chieu/")
    
    print("Đang lấy phim sắp chiếu...")
    galaxy_coming_soon = get_galaxy_movies_selenium("https://www.galaxycine.vn/phim-sap-chieu/")

    galaxy_movies = {
        "now_showing": galaxy_now_showing,
        "coming_soon": galaxy_coming_soon
    }

    script_dir = os.path.dirname(os.path.abspath(__file__))
    json_path = os.path.join(script_dir, "movies.json")

    # Lưu dữ liệu vào JSON trong cùng thư mục với app.py
    with open(json_path, "w", encoding="utf-8") as f:
        json.dump(galaxy_movies, f, ensure_ascii=False, indent=4)
    
    # In số lượng phim đã lấy được
    print(f"Tổng số phim đang chiếu: {len(galaxy_now_showing)}")
    print(f"Tổng số phim sắp chiếu: {len(galaxy_coming_soon)}")
except Exception as e:
    print(f"Lỗi: {e}")