// 툴팁 위치 계산
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('mousemove', function(e) {
        // 집밥 원가 툴팁
        const cookingLabel = e.target.closest('.cooking-cost-label');
        if (cookingLabel) {
            cookingLabel.style.setProperty('--tooltip-left', e.clientX + 'px');
            cookingLabel.style.setProperty('--tooltip-top', (e.clientY - 80) + 'px');
        }

        // 배달 가격 툴팁
        const deliveryLabel = e.target.closest('.delivery-price-label');
        if (deliveryLabel) {
            deliveryLabel.style.setProperty('--delivery-tooltip-left', e.clientX + 'px');
            deliveryLabel.style.setProperty('--delivery-tooltip-top', (e.clientY - 80) + 'px');
        }
    });
});

