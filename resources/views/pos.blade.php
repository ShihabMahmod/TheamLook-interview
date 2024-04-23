<!DOCTYPE html>
<html>
<head>
    <title>Products and Cart</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<style>

    #product-list {
        display: grid; /* Use CSS Grid for layout */
        grid-template-columns: repeat(2, 1fr); /* Two columns of equal width */
        grid-gap: 20px; /* Add spacing between product items */
        margin-top: 20px; /* Add margin at the top */
    }

    /* Each product item */
    .product-item {
        border: 1px solid #ccc; /* Add a border */
        padding: 10px; /* Add padding inside the item */
        border-radius: 8px; /* Add rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add a subtle box shadow */
        text-align: center; /* Center-align text */
    }

    /* Product image styling */
    .product-image {
        width: 100%; /* Full width */
        height: auto; /* Maintain aspect ratio */
        object-fit: cover; /* Cover the image in the space */
        border-radius: 8px; /* Rounded corners */
    }

    /* Product name styling */
    .product-item h4 {
        margin-bottom: 10px;
        font-size: 1.2em;
        font-weight: bold;
    }

    /* SKU and price styling */
    .product-item p {
        margin: 5px 0;
    }

    /* Add to cart button styling */
    .add-to-cart-btn {
        display: block;
        width: 100%;
        padding: 10px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-align: center;
        margin-top: 10px;
    }

    .add-to-cart-btn:hover {
        background-color: #0056b3; /* Darker blue on hover */
    }

</style>
<body>
    <div class="container">
        <div class="row">
            
            <div class="col-md-8" id="product-list-section">
                <h2>Product List</h2>
                
                <form action="{{ route('pos') }}" method="GET">
                    <div class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by name or SKU" value="{{ request()->input('search') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>

                <a href="{{route('order-list')}}">Order List</a>
            
                <div id="product-list">
                    @foreach($products as $product)
                        <div class="product-item">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                            <h4>{{ $product->name }}</h4>
                            <p>SKU: {{ $product->sku }}</p>
                            <p>Selling Price: ${{ $product->selling_price }}</p>
                            <button class="add-to-cart-btn" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-price="{{ $product->selling_price }}">
                                Add to Cart
                            </button>
                        </div>
                    @endforeach
                    
                    {{ $products->links() }}
                </div>
            </div>


            <div class="col-md-4" id="shopping-cart-section">
                <h2>Shopping Cart</h2>
                <div id="shopping-cart">
                  
                    <ul id="cart-items"></ul>
                    <p>Total: $<span id="cart-total">0.00</span></p>
                    <button id="checkout-btn">Checkout</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            const cart = [];
            let total = 0;

            
            function updateCart() {
                const cartItems = $('#cart-items');
                cartItems.empty();
                total = 0;

                cart.forEach((item, index) => {
                    total += item.price * item.quantity;
                    cartItems.append(`
                        <li>
                            ${item.name} - $${item.price} x ${item.quantity}
                            <input type="number" value="${item.quantity}" min="1" data-index="${index}" class="update-quantity">
                            <button class="remove-from-cart" data-index="${index}">Remove</button>
                        </li>
                    `);
                });

                $('#cart-total').text(total.toFixed(2));
            }

            $('.add-to-cart-btn').on('click', function() {
                const productId = $(this).data('product-id');
                const productName = $(this).data('product-name');
                const productPrice = parseFloat($(this).data('product-price'));

               
                const existingProductIndex = cart.findIndex(item => item.id === productId);

                if (existingProductIndex > -1) {
                
                    cart[existingProductIndex].quantity++;
                } else {
                   
                    cart.push({ id: productId, name: productName, price: productPrice, quantity: 1 });
                }

                updateCart();
            });

            $(document).on('input', '.update-quantity', function() {
                const index = $(this).data('index');
                const newQuantity = parseInt($(this).val());

                if (newQuantity > 0) {
                    cart[index].quantity = newQuantity;
                    updateCart();
                }
            });

           
            $(document).on('click', '.remove-from-cart', function() {
                const index = $(this).data('index');
                cart.splice(index, 1);
                updateCart();
            });

          
            $('#checkout-btn').on('click', function() {
                if (cart.length > 0) {
                   
                    handleCheckout()
                    
                } else {
                    alert('Your cart is empty.');
                }
            });

            function handleCheckout() {
                const checkoutData = {
                    cart: cart, 
                    total: total
                };
                const csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '/checkout',
                    method: 'POST',
                    data: JSON.stringify(checkoutData),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                       
                        alert('Order placed successfully!');
                        cart.length = 0; 
                        updateCart();
                    },
                    error: function(xhr, status, error) {
                       
                        alert('Failed to place order: ' + error);
                    }
                });

            }

        });


    
    </script>
</body>
</html>
