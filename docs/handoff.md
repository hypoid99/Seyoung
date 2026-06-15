# 🤝 세영건재 쇼핑몰 개발 핸드오프 (Handoff) 문서

본 문서는 새로운 대화창에서 개발을 계속 이어서 진행할 수 있도록 프로젝트의 현재 상태, 구현된 기능, 기술 환경 및 다음 단계 작업 목록을 정리한 핸드오프 파일입니다.

---

## 📌 1. 프로젝트 기본 정보
* **프로젝트 이름**: 세영건재 온라인 쇼핑몰
* **개발 환경**: 로컬 개발 (XAMPP v8.2.12 - Apache, MySQL, PHP 8.2.12)
* **웹 루트 경로**: [C:/xampp/htdocs/seyoung](file:///C:/xampp/htdocs/seyoung) (Git 저장소 일치)
* **데이터베이스**: `seyoung_db`
* **관리자 계정**: `admin`
* **원격 저장소**: [GitHub Repository (Seyoung.git)](https://github.com/hypoid99/Seyoung.git)

---

## 🛠️ 2. 현재 개발 상태 및 진행 내역 (1~4차 완료)
현재 1차부터 4차까지 계획된 핵심 뼈대 및 커스텀 플러그인 모듈 개발이 로컬에서 모두 완료되었으며, 작동성이 성공적으로 입증되었습니다.

### 🎨 테마 및 디자인 프레임워크 (`wp-content/themes/`)
* **Astra 부모 테마**: 웹 스토어 최적화를 위해 경량 부모 테마로 채택
* **[seyoung-theme](file:///C:/xampp/htdocs/seyoung/wp-content/themes/seyoung-theme/) (자식 테마)**:
  * 따뜻한 베이지 & 브라운 톤의 CSS 디자인 시스템 적용 (`style.css` 내 CSS 변수 선언)
  * `front-page.php` 메인 레이아웃 구현 (히어로 배너, 6대 카테고리 퀵그리드, 최신 우커머스 상품 4개 그리드 동적 출력, 대량견적 CTA 배너, 푸터 정보 노출)
  * `single-seyoung_gallery.php` (시공 사례 상세 페이지) 구현 (Before/After 5:5 뷰 및 사용 제품 우커머스 상품 연동 노출)

### 🔌 커스텀 개발 플러그인 (`wp-content/plugins/`)
우커머스의 코어 업데이트 안정성을 보장하기 위해 모든 특화 기능은 커스텀 플러그인으로 분리 구현되었습니다.

1. **[seyoung-tile-calculator](file:///C:/xampp/htdocs/seyoung/wp-content/plugins/seyoung-tile-calculator/seyoung-tile-calculator.php) (타일 계산기)**:
   * 상품 메타 `_tile_area_per_box`(박스당 면적 ㎡) 등록
   * 상품 상세 페이지에 실시간 소요량 계산기 UI 탑재 및 계산된 박스 수량이 우커머스 수량 필드(`qty`)에 즉시 동기화되어 카트에 담기도록 연동.
2. **[seyoung-board](file:///C:/xampp/htdocs/seyoung/wp-content/plugins/seyoung-board/seyoung-board.php) (1:1 문의 게시판)**:
   * 커스텀 포스트 타입(`seyoung_qna`) 등록 및 단축코드 `[seyoung_qna_board]` 제공
   * '비밀글' 체크 시 워드프레스 기본 `private` 상태값 부여로 작성자와 관리자 외 접근 완벽 제어
   * 관리자가 댓글 작성 시 문의 글 메타가 자동으로 '답변완료' 상태로 업데이트
3. **[seyoung-gallery](file:///C:/xampp/htdocs/seyoung/wp-content/plugins/seyoung-gallery/seyoung-gallery.php) (시공 사례 갤러리)**:
   * 커스텀 포스트 타입(`seyoung_gallery`) 등록 및 단축코드 `[seyoung_gallery_list]` 제공
   * 메타 박스 형태로 시공 전(Before) 이미지 등록 및 시공에 사용된 자재들을 우커머스 상품 목록 중에서 체크하여 매핑 가능
4. **[seyoung-grade-price](file:///C:/xampp/htdocs/seyoung/wp-content/plugins/seyoung-grade-price/seyoung-grade-price.php) (시공기사 등급별 우대가)**:
   * 새로운 사용자 역할 **'시공기사'(`contractor`)** 추가
   * 상품 편집 일반 탭에 '시공기사 우대가'(`_contractor_price`) 필드 신설
   * `contractor` 등급 로그인 시 상점 내 상품 가격 필터 훅을 조작하여 상세, 목록, 장바구니, 결제창 전역에 할인가 즉시 적용 및 우대가 배지 활성화
5. **[seyoung-quote](file:///C:/xampp/htdocs/seyoung/wp-content/plugins/seyoung-quote/seyoung-quote.php) (대량 견적 요청 폼)**:
   * 커스텀 포스트 타입(`seyoung_quote`) 등록 및 단축코드 `[seyoung_quote_form]` 제공
   * 고객 정보 및 도면/명세 PDF 파일 업로드 지원 및 접수 내역 DB 적재 및 이메일(`wp_mail`) 전송
6. **[seyoung-stock-badge](file:///C:/xampp/htdocs/seyoung/wp-content/plugins/seyoung-stock-badge/seyoung-stock-badge.php) (실시간 재고 표시 배지)**:
   * 상품 목록 및 상세 페이지의 가격 부근에 실시간 재고 훅 연결
   * 재고 10개 이하 시 **`🔥 품절 임박 (남은 수량: X개)`** 펄스 애니메이션 배지, 품절 시 **`❌ 일시 품절`** 회색 배지 노출

---

## 🔎 3. 다음 대화창에서 진행할 작업 (Next Steps)

1. **사장님(유저)의 로컬 자가 검증**:
   * 로컬 사이트(`localhost/seyoung`)에서 통합본에 제공된 6가지 시나리오(장바구니 결제, 계산기 연동, 1:1 Q&A 비밀글 및 댓글 답변, 시공 갤러리 등록 및 연계 상품, 시공기사 계정 전환 후 가격 할인, 대량 견적 전송 및 재고 배지 동작)를 직접 테스트해봅니다.
2. **디자인 및 레이아웃 커스텀 조정**:
   * 메인 화면의 카테고리 퀵 링크, 헤더 및 푸터 정보, 상점 아카이브 레이아웃 디자인(폰트, 간격, 컬러 디테일)에 대한 피드백을 반영해 디테일을 보완합니다.
3. **2단계 닷홈(Dothome) 호스팅 배포 준비**:
   * 로컬 검증이 완전히 완료되면, 닷홈 무료 호스팅 공간으로 마이그레이션(이전)을 계획합니다.
   * 워드프레스 백업/마이그레이션 도구인 `All-in-One WP Migration` 플러그인 또는 `Better Search Replace`를 이용해 DB 주소(도메인) 직렬화 깨짐을 예방하며 호스팅 서버로 코드를 FTP 업로드하는 실습을 진행합니다.

---

## 📁 4. 참고용 프로젝트 문서 (docs/)
새로운 대화창이 시작되면 제일 먼저 아래 문서를 검토하여 맥락을 파악하도록 설계되어 있습니다.
* **[개발계획서.md](file:///C:/xampp/htdocs/seyoung/docs/세영건재_쇼핑몰_개발계획서.md)**: v4.2 전체 마스터 플랜 및 기술 정책
* **[1~4차_구현계획_통합본.md](file:///C:/xampp/htdocs/seyoung/docs/1~4차_구현계획_통합본.md)**: 각 플러그인별 세부 작동 구조 및 검증 시나리오

---

## 💬 5. 새로운 대화창 시작용 프롬프트 (새 세션 AI 전달용)
새로운 대화창을 시작할 때 아래 프롬프트를 통째로 복사해서 첫 입력값으로 넣어주세요.

```text
우리는 워드프레스 + 우커머스 + Astra 자식 테마 기반으로 "세영건재" 쇼핑몰을 개발 중인 파트너입니다.
현재 로컬 개발(1~4차) 환경 세팅과 커스텀 플러그인 6개 개발이 모두 끝난 상태입니다.

프로젝트 루트의 `docs/` 폴더에 다음 문서들을 저장해 두었습니다.
1. docs/handoff.md (새 세션 인수인계 가이드)
2. docs/1~4차_구현계획_통합본.md (플러그인 기능 및 검증 시나리오)
3. docs/세영건재_쇼핑몰_개발계획서.md (마스터 플랜 v4.2)

먼저 `docs/handoff.md`를 포함한 문서들을 읽어 현재까지의 세부 사항과 작업 구조를 파악해 주세요.
그다음, 이어서 진행해야 할 '로컬 자가 검증 방법' 및 '닷홈(Dothome) 배포를 위한 마이그레이션 준비' 작업을 함께 시작해 봅시다!
```
