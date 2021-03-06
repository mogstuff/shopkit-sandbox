<?php

return function($site, $pages, $page) {

    // Initialize cart
    $cart = Cart::getCart();

    // Handle cart updates
    if ($action = get('action')) {
        $id = get('id', implode('::', array(get('uri', ''), get('variant', ''), get('option', ''))));
        $quantity = intval(get('quantity'));
        $variant = get('variant');
        $option = get('option');
        switch ($action) {
            case 'add':
                $cart->add($id, $quantity);
            break;
            case 'remove':
                $cart->remove($id);
                break;
            case 'update':
                $cart->update($id, $quantity);
                break;
            case 'delete':
                $cart->delete($id);
                break;
        }
    }
    
    // Add properties to cart items
    $items = $cart->getItems();
    foreach ($items as $item) {

        // Image src (base64 encoded)
        $img = $pages->findByUri($item->uri)->images()->first();
        if (!$img) {
            $item->imgSrc = false;
        } else {
            $item->imgSrc = thumb($img,['width'=>60, 'height'=>60, 'crop'=>true])->dataUri();
        }

        // Max quantity
        foreach (page($item->uri)->variants()->toStructure() as $variant) {
            if (str::slug($variant->name()) === $item->variant) {

                // Determine if we're at the maximum quantity
                $siblingsQty = 0;
                foreach ($cart->data as $key => $qty) {
                    if (strpos($key, $item->uri.'::'.$item->variant) === 0 and $key != $item->id) $siblingsQty += $qty;
                }
                // Determine if we are at the maximum quantity
                if (inStock($variant) !== true and inStock($variant) <= ($item->quantity + $siblingsQty)) {
                    $item->maxQty = true;
                } else {
                    $item->maxQty = false;
                }
            }
        }

        // Price text
        if ($item->sale_amount) {
            $item->priceText = formatPrice($item->sale_amount * $item->quantity).'<del>'.formatPrice($item->amount * $item->quantity).'</del>';
        } else {
            $item->priceText = formatPrice($item->amount * $item->quantity);
        }

    }

    // Get countries
    $countries = page('/shop/countries')->children()->invisible();

    // Get shipping rates
    $shipping_rates = $cart->getShippingRates();


    // Set shipping method as a session variable
    // Shipping method is an array containing 'title' and 'rate'
    $shippingMethods = $cart->getShippingRates();
    if (get('shipping')) {
      // First option: see if a shipping method was set through a form submission
      if (get('shipping') == 'free-shipping') {
        $shippingMethod = [
          'title' => l::get('free-shipping'),
          'rate' => 0
        ];
      }
      foreach ($shippingMethods as $key => $method) {
        if (get('shipping') == str::slug($method['title'])) {
          $shippingMethod = $method;
        }
      }
    } else if (s::get('shipping')) {
      // Second option: the shipping has already been set in the session
      $shippingMethod = s::get('shipping');
    } else {
      // Last resort: choose the first shipping method
      $shippingMethod = array_shift($shippingMethods);      
    }
    s::set('shipping',$shippingMethod);

    // Get selected shipping rate
    $shipping = s::get('shipping');

    return [
        'cart' => $cart,
        'items' => $items,
        'countries' => $countries,
        'shipping_rates' => $shipping_rates,
        'shipping' => $shipping,
    ];
};