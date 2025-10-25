// 툴팁 위치 계산 (반응형)
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('mousemove', function(e) {
        const isMobile = window.innerWidth < 768;
        const tooltipWidth = isMobile ? 200 : 224;
        const tooltipOffset = isMobile ? 60 : 80;

        // 화면 경계 체크
        let leftPosition = e.clientX;
        const rightEdge = leftPosition + (tooltipWidth / 2);
        const leftEdge = leftPosition - (tooltipWidth / 2);

        // 오른쪽 경계 초과 체크
        if (rightEdge > window.innerWidth - 10) {
            leftPosition = window.innerWidth - (tooltipWidth / 2) - 10;
        }

        // 왼쪽 경계 초과 체크
        if (leftEdge < 10) {
            leftPosition = (tooltipWidth / 2) + 10;
        }

        // 상단 위치 계산
        let topPosition = e.clientY - tooltipOffset;
        if (topPosition < 10) {
            topPosition = e.clientY + 20; // 위에 공간이 없으면 아래로
        }

        // 집밥 원가 툴팁
        const cookingLabel = e.target.closest('.cooking-cost-label');
        if (cookingLabel) {
            cookingLabel.style.setProperty('--cooking-tooltip-left', leftPosition + 'px');
            cookingLabel.style.setProperty('--cooking-tooltip-top', topPosition + 'px');
        }

        // 배달 가격 툴팁
        const deliveryLabel = e.target.closest('.delivery-price-label');
        if (deliveryLabel) {
            deliveryLabel.style.setProperty('--delivery-tooltip-left', leftPosition + 'px');
            deliveryLabel.style.setProperty('--delivery-tooltip-top', topPosition + 'px');
        }

        // 절약률 툴팁
        const savingsLabel = e.target.closest('.savings-rate-label');
        if (savingsLabel) {
            savingsLabel.style.setProperty('--savings-tooltip-left', leftPosition + 'px');
            savingsLabel.style.setProperty('--savings-tooltip-top', topPosition + 'px');
        }
    });
});

