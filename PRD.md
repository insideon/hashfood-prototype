# HashFood PRD (Product Requirements Document)

## 1. Executive Summary

### 1.1 Product Overview
**HashFood**는 "오늘 해 먹을까, 시켜 먹을까?"라는 일상적 고민을 데이터 기반으로 해결하는 합리적 소비 어시스턴트 플랫폼입니다. 식자재 원가, 레시피 분석, 배달비 비교를 통해 가장 경제적인 식사 선택을 제안합니다.

### 1.2 Vision
불투명한 식비 구조를 데이터 기반으로 재구성하여, 건강하고 합리적인 식문화 생태계를 만들어갑니다.

### 1.3 Mission
- 식자재 가격과 레시피 사용량 기반의 **정확한 원가 계산**
- 배달 음식 대비 **실질적 절약률 시각화**
- AI 학습 기반 **개인화된 식비 판단 지원**

---

## 2. Problem Statement

### 2.1 Current Pain Points
1. **불투명한 식비 구조**: 집밥 vs 배달 음식의 실제 비용 비교가 어려움
2. **비합리적 의사결정**: 감정적, 즉흥적 식사 선택으로 인한 과소비
3. **정보 부족**: 식자재 가격 변동성과 실사용량에 대한 정확한 데이터 부재
4. **패턴 인식 실패**: 개인의 소비 습관과 생활 패턴을 고려하지 못한 선택

### 2.2 Target Users
- **Primary**: 1인 가구, 맞벌이 부부 (25-40세)
- **Secondary**: 식비 절약에 관심 있는 가정
- **Characteristics**:
  - 합리적 소비를 추구하지만 시간 부족
  - 배달 음식과 자취 요리 사이에서 고민
  - 데이터 기반 의사결정 선호

---

## 3. Product Goals & Success Metrics

### 3.1 Business Goals
- **Year 1**: MAU 10만 명 확보
- **Year 2**: 식비 절감 총액 100억 원 달성
- **Year 3**: 식자재 유통 파트너십 구축

### 3.2 User Success Metrics
- 월평균 식비 절감률: **15-25%**
- 주간 활성 사용자 비율: **60%+**
- 추천 수용률: **40%+**
- 사용자 만족도 (NPS): **50+**

### 3.3 Product Metrics
- 원가 계산 정확도: **±5% 이내**
- AI 추천 정확도: **70%+ (3개월 학습 후)**
- 평균 세션 시간: **3-5분**
- 데이터 업데이트 주기: **일 1회 (식자재 가격)**

---

## 4. Core Features

### 4.1 Feature Priority Matrix

| Priority | Feature | Description | MVP |
|----------|---------|-------------|-----|
| P0 | 원가 계산 엔진 | 식자재 가격 × 레시피 사용량 기반 실제 원가 산출 | ✅ |
| P0 | 배달 vs 집밥 비교 | 배달 음식 대비 절약률 실시간 계산 | ✅ |
| P0 | 레시피 데이터베이스 | 주요 메뉴별 재료 구성 및 사용량 정보 | ✅ |
| P1 | AI 추천 시스템 | 사용자 패턴 학습 기반 맞춤 제안 | ⏳ |
| P1 | 식자재 가격 트래킹 | 실시간 시장 가격 크롤링 및 변동 알림 | ⏳ |
| P2 | 소비 분석 대시보드 | 월별/주별 식비 리포트 및 인사이트 | ⏳ |
| P2 | 커뮤니티 레시피 공유 | 사용자 생성 레시피 및 원가 공유 | 🔜 |
| P3 | 식자재 공동구매 | 그룹 단위 할인 구매 기능 | 🔜 |

---

## 5. Feature Specifications

### 5.1 원가 계산 엔진

#### 5.1.1 User Story
> "As a user, I want to know the exact cost of cooking a specific dish, so that I can compare it with delivery options."

#### 5.1.2 Functional Requirements
- **Input**:
  - 메뉴 선택 (예: 김치찌개, 된장찌개, 파스타)
  - 인분 수 (1-4인분)
  - 선호 식자재 품질 (일반/프리미엄)

- **Processing**:
  ```
  총 원가 = Σ(식자재 단가 × 레시피 사용량)
  1인분 원가 = 총 원가 / 인분 수
  부가 비용 = 조리 시간 환산 비용 (선택적)
  ```

- **Output**:
  - 총 재료비 (원)
  - 1인분 원가 (원)
  - 주요 재료 비용 분해 (상위 5개)
  - 예상 조리 시간

#### 5.1.3 Technical Specifications
```php
// Example Model Structure
class Recipe extends Model
{
    public function ingredients(): HasMany
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    public function calculateCost(int $servings = 1): float
    {
        return $this->ingredients->sum(function ($ingredient) use ($servings) {
            return $ingredient->quantity * $servings * $ingredient->current_price;
        });
    }
}
```

#### 5.1.4 Acceptance Criteria
- [ ] 30초 이내 원가 계산 완료
- [ ] 실제 시장 가격 대비 ±5% 오차 범위
- [ ] 모바일/웹 반응형 UI
- [ ] 재료별 가격 출처 표시

---

### 5.2 배달 vs 집밥 비교

#### 5.2.1 User Story
> "As a user, I want to see how much I can save by cooking instead of ordering delivery, with visual comparisons."

#### 5.2.2 Functional Requirements
- **비교 항목**:
  - 배달 음식 가격 (주요 플랫폼 평균)
  - 집밥 원가 (재료비)
  - 절약 금액 및 절약률 (%)
  - 조리 시간 vs 배달 시간

- **시각화**:
  - 막대 그래프: 비용 비교
  - 누적 절약 금액 카운터
  - 월간 절약 트렌드

#### 5.2.3 UI/UX Requirements
```
┌─────────────────────────────────┐
│  🍜 김치찌개 (2인분)              │
├─────────────────────────────────┤
│ 배달 주문     ₩18,000            │
│ 집에서 요리   ₩4,500    -75% 💰  │
├─────────────────────────────────┤
│ 절약 금액: ₩13,500               │
│ 조리 시간: 25분 vs 배달 40분      │
└─────────────────────────────────┘
```

---

### 5.3 AI 추천 시스템

#### 5.3.1 User Story
> "As a user, I want personalized recommendations based on my cooking habits, budget, and preferences."

#### 5.3.2 Machine Learning Features
- **학습 데이터**:
  - 과거 선택 이력 (집밥/배달)
  - 요일/시간대별 패턴
  - 예산 범위
  - 선호 메뉴 카테고리
  - 냉장고 재고 (연동 시)

- **추천 로직**:
  ```
  추천 점수 = (절약률 × 0.4)
            + (선호도 × 0.3)
            + (조리 용이성 × 0.2)
            + (재료 활용도 × 0.1)
  ```

#### 5.3.3 MVP Approach
- Phase 1: 룰 기반 추천 (if-else logic)
- Phase 2: 협업 필터링 (similar users)
- Phase 3: 딥러닝 모델 (TensorFlow/PyTorch)

---

### 5.4 식자재 가격 트래킹

#### 5.4.1 Data Sources
- 오픈마켓 API (쿠팡, 마켓컬리 등)
- 농수산물유통정보 (KAMIS)
- 크롤링: 대형마트 온라인몰

#### 5.4.2 Update Cycle
- 실시간: 온라인 가격 (API)
- 일 1회: 크롤링 데이터
- 주 1회: 오프라인 매장 샘플링

#### 5.4.3 Price Anomaly Detection
```php
// Alert when price changes > 20%
if ($newPrice > $oldPrice * 1.2) {
    Notification::send($user, new PriceAlert($ingredient));
}
```

---

## 6. User Flow

### 6.1 First-Time User Journey
```
1. 온보딩 → 선호 메뉴 선택 (3-5개)
2. 평균 식비 입력
3. 주요 식자재 가격 체크 (자동 수집)
4. 첫 비교 분석 보기
5. 푸시 알림 권한 요청
```

### 6.2 Daily Use Case
```
오전 11시: "점심 뭐 먹지?" 푸시 알림
→ 앱 오픈
→ "김치찌개 vs 배달 한식" 비교 카드 확인
→ "집밥" 선택
→ 레시피 재료 리스트 확인
→ 부족한 재료 쇼핑 링크 제공
```

---

## 7. Technical Architecture

### 7.1 Tech Stack
- **Backend**: Laravel 12 (PHP 8.3)
- **Frontend**: Livewire 3 + Volt + Flux UI
- **Database**: MySQL 8.0
- **AI/ML**: Python (FastAPI) + scikit-learn
- **Cache**: Redis
- **Queue**: Laravel Queue (Redis driver)
- **Storage**: AWS S3 (레시피 이미지)

### 7.2 System Architecture
```
┌─────────────┐
│   User App  │ (Livewire SPA)
└──────┬──────┘
       │
┌──────▼──────────────────┐
│  Laravel API Server     │
│  - Authentication       │
│  - Recipe Engine        │
│  - Price Aggregator     │
└──────┬──────────────────┘
       │
┌──────▼──────┐  ┌────────────┐
│   MySQL     │  │   Redis    │
│  (Main DB)  │  │  (Cache)   │
└─────────────┘  └────────────┘
       │
┌──────▼─────────────────┐
│  External Services     │
│  - KAMIS API           │
│  - E-commerce APIs     │
│  - Web Scrapers        │
└────────────────────────┘
```

### 7.3 Database Schema (Core Tables)
```sql
-- Recipes
recipes (id, name, description, cooking_time, difficulty, servings)

-- Ingredients
ingredients (id, name, category, unit, current_price, price_updated_at)

-- Recipe-Ingredient Junction
recipe_ingredients (recipe_id, ingredient_id, quantity, is_optional)

-- User Preferences
user_preferences (user_id, favorite_recipes, budget_limit, dietary_restrictions)

-- User Activity Log
activity_logs (user_id, recipe_id, decision_type, saved_amount, created_at)

-- Price History
price_histories (ingredient_id, price, source, recorded_at)
```

---

## 8. MVP Scope

### 8.1 MVP Features (Launch in 3 months)
✅ **Must Have**:
- 50개 주요 메뉴 레시피
- 200개 핵심 식자재 가격 DB
- 원가 계산 + 배달 비교
- 회원가입 / 로그인 (Fortify)
- 모바일 반응형 UI

⏳ **Should Have**:
- 간단한 룰 기반 추천 (요일/시간대)
- 주간 리포트 (절약 금액 요약)

🔜 **Nice to Have** (Post-MVP):
- AI 기반 개인화 추천
- 커뮤니티 기능
- 식자재 공동구매

### 8.2 MVP Success Criteria
- 1,000명 베타 테스터 확보
- 평균 월 1만 원 이상 절약 달성
- 주 2회 이상 재방문율 50%

---

## 9. Non-Functional Requirements

### 9.1 Performance
- API Response Time: < 500ms (p95)
- Page Load Time: < 2s (First Contentful Paint)
- Database Query: < 100ms (인덱싱 최적화)

### 9.2 Security
- HTTPS 전용 (Let's Encrypt)
- Laravel Sanctum (API 인증)
- CSRF/XSS 보호 (Laravel 기본)
- 개인정보 암호화 (식단 기록 등)

### 9.3 Scalability
- Horizontal Scaling (Load Balancer)
- Database Sharding 준비 (user_id 기준)
- CDN (이미지/정적 파일)

### 9.4 Accessibility
- WCAG 2.1 AA 준수
- 스크린 리더 지원
- 키보드 네비게이션

---

## 10. Monetization Strategy

### 10.1 Revenue Streams
1. **프리미엄 구독** (₩4,900/월)
   - 무제한 레시피 접근
   - AI 맞춤 추천
   - 냉장고 재고 관리
   - 광고 제거

2. **제휴 수수료**
   - 식자재 쇼핑 링크 클릭 (5-10%)
   - 배달앱 연동 (CPA)

3. **B2B 데이터 판매**
   - 식자재 가격 트렌드 리포트
   - 소비자 선호도 분석 (익명화)

### 10.2 Pricing Tiers
| Tier | Price | Features |
|------|-------|----------|
| Free | ₩0 | 기본 10개 레시피, 배달 비교 |
| Basic | ₩2,900/월 | 50개 레시피, 주간 리포트 |
| Premium | ₩4,900/월 | 무제한 + AI 추천 + 재고 관리 |

---

## 11. Risks & Mitigation

### 11.1 Technical Risks
| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| 가격 데이터 부정확 | High | Medium | 다중 소스 검증, 사용자 신고 |
| API 제공 중단 | High | Low | 대체 크롤러 구축 |
| 서버 과부하 | Medium | Medium | 캐싱, CDN, Auto Scaling |

### 11.2 Business Risks
| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| 사용자 확보 실패 | High | Medium | 초기 무료 서비스, 바이럴 마케팅 |
| 경쟁 서비스 등장 | Medium | High | 차별화된 AI 기능, 커뮤니티 강화 |
| 수익화 거부감 | Medium | Low | 충분한 무료 기능 제공 |

---

## 12. Go-to-Market Strategy

### 12.1 Launch Plan
- **Week 1-2**: 페이스북/인스타그램 광고 (1인 가구 타겟)
- **Week 3-4**: 맘카페/커뮤니티 마케팅
- **Month 2**: 인플루언서 협업 (요리 유튜버)
- **Month 3**: PR (언론 보도자료)

### 12.2 Growth Hacking
- 친구 초대 시 프리미엄 1개월 무료
- 월간 절약왕 챌린지 (리워드)
- 레시피 공유 시 포인트 적립

---

## 13. Roadmap

### 13.1 Timeline
```
Q1 2025 (MVP)
├─ Week 1-4:   Core 엔진 개발 (원가 계산)
├─ Week 5-8:   UI/UX 구현 (Livewire + Flux)
├─ Week 9-10:  가격 데이터 수집 자동화
└─ Week 11-12: 베타 테스트 & 버그 수정

Q2 2025 (Growth)
├─ AI 추천 시스템 v1
├─ 소셜 공유 기능
└─ 제휴 마케팅 시작

Q3 2025 (Scale)
├─ 커뮤니티 레시피 플랫폼
├─ 냉장고 재고 관리 (바코드 스캔)
└─ 음성 AI 어시스턴트 (Siri/Bixby 연동)

Q4 2025 (Ecosystem)
├─ 식자재 공동구매 런칭
├─ 오프라인 파트너십 (로컬 마트)
└─ B2B SaaS 버전 (기업 구내식당)
```

---

## 14. Team & Responsibilities

### 14.1 Core Team
- **Product Manager**: PRD 관리, 우선순위 결정
- **Lead Developer** (Laravel): 백엔드 아키텍처, API 개발
- **Frontend Developer** (Livewire): UI/UX 구현
- **Data Engineer**: 가격 크롤링, ETL 파이프라인
- **ML Engineer** (Part-time): AI 추천 시스템
- **Designer** (Contract): UI/UX 디자인

### 14.2 External Partners
- 법률 자문: 개인정보 보호법 검토
- 마케팅 에이전시: 초기 런칭 캠페인

---

## 15. Success Tracking

### 15.1 KPIs (Monthly)
```
User Acquisition
├─ New Signups: 5,000+
├─ Conversion Rate (Free → Paid): 5%
└─ CAC (Customer Acquisition Cost): < ₩3,000

Engagement
├─ DAU/MAU Ratio: 30%+
├─ Avg. Session Duration: 4분+
└─ Feature Usage Rate (비교 기능): 80%+

Retention
├─ D7 Retention: 40%+
├─ D30 Retention: 20%+
└─ Churn Rate: < 10%

Revenue
├─ MRR (Monthly Recurring Revenue): ₩500만 (Month 6)
└─ ARPU (Average Revenue Per User): ₩2,000
```

### 15.2 Analytics Tools
- Google Analytics 4 (웹/앱 트래킹)
- Mixpanel (사용자 행동 분석)
- Hotjar (히트맵, 세션 녹화)
- Sentry (에러 모니터링)

---

## 16. Appendix

### 16.1 Glossary
- **원가**: 레시피 재료비 합계
- **절약률**: (배달 가격 - 원가) / 배달 가격 × 100
- **조리 용이성**: 레시피 난이도 (1-5점)

### 16.2 References
- [KAMIS 농산물 유통정보](https://www.kamis.or.kr)
- [식품의약품안전처 레시피 DB](https://www.foodsafetykorea.go.kr)
- [통계청 가계동향조사](https://kostat.go.kr)

### 16.3 Revision History
| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2025-01-24 | Product Team | Initial PRD |

---

**Document Owner**: Product Manager
**Last Updated**: 2025-01-24
**Status**: Draft → Review → Approved → In Development
