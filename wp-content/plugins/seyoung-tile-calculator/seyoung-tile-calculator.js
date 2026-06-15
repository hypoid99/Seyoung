/**
 * Seyoung Tile Calculator JS
 */
jQuery(document).ready(function($) {
    var $wrap = $('.seyoung-tile-calculator-wrap');
    if ( ! $wrap.length ) {
        return;
    }

    var areaPerBox = parseFloat($wrap.data('area-per-box'));
    var productPrice = parseFloat($wrap.data('product-price')) || 0;
    
    var $widthInput = $('#tile-calc-width');
    var $heightInput = $('#tile-calc-height');
    var $lossSelect = $('#tile-calc-loss');
    
    var $resArea = $('#tile-calc-res-area');
    var $resBoxes = $('#tile-calc-res-boxes');
    var $resPrice = $('#tile-calc-res-price');
    
    // Find the WooCommerce quantity input field
    var $qtyInput = $('form.cart').find('input.qty');

    function calculate() {
        var width = parseFloat($widthInput.val()) || 0;
        var height = parseFloat($heightInput.val()) || 0;
        var loss = parseFloat($lossSelect.val()) || 0;
        
        if ( width <= 0 || height <= 0 ) {
            $resArea.text('0.00');
            $resBoxes.text('0');
            $resPrice.text('0');
            return;
        }

        // 1. Calculate net area
        var netArea = width * height;
        
        // 2. Calculate total area with loss margin
        var totalArea = netArea * (1 + (loss / 100));
        
        // 3. Calculate necessary box quantity (Round up to next whole box)
        var boxes = Math.ceil(totalArea / areaPerBox);
        
        // 4. Calculate estimated price
        var estimatedPrice = boxes * productPrice;

        // Render calculated values to the UI
        $resArea.text(totalArea.toFixed(2));
        $resBoxes.text(boxes);
        $resPrice.text(estimatedPrice.toLocaleString('ko-KR'));
        
        // 5. Update WooCommerce Quantity Input
        if ( $qtyInput.length ) {
            $qtyInput.val(boxes).trigger('change');
        }
    }

    // Bind event listeners for input fields
    $widthInput.on('input change', calculate);
    $heightInput.on('input change', calculate);
    $lossSelect.on('change', calculate);
});
