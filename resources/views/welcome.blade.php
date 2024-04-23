<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <style>
        /* resources/my_views/styles.css */
        form {
            margin: 20px;
        }

        form div {
            margin-bottom: 10px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        input[type="date"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Styling for products list and shopping cart */
        .product-item,
        .order-item {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .container {
            padding: 20px;
        }

    </style>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

    </head>
    <body class="antialiased">
        
    <a href="{{route('pos')}}">POS</a>
            <form action="{{route('products.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div>
                <label for="sku">Product SKU:</label>
                <input type="text" id="sku" name="sku" required>
            </div>

            <div>
                <label for="unit">Product Unit:</label>
                <input type="text" id="unit" name="unit" required>
            </div>

            <div>
                <label for="unit_value">Product Unit Value:</label>
                <input type="number" id="unit_value" name="unit_value" required>
            </div>

            <div>
                <label for="selling_price">Selling Price:</label>
                <input type="number" id="selling_price" name="selling_price" step="0.01" required>
            </div>

            <div>
                <label for="purchase_price">Purchase Price:</label>
                <input type="number" id="purchase_price" name="purchase_price" step="0.01" required>
            </div>

            <div>
                <label for="discount">Discount (%):</label>
                <input type="number" id="discount" name="discount" step="0.01" min="0" max="100" required>
            </div>

            <div>
                <label for="tax">Tax (%):</label>
                <input type="number" id="tax" name="tax" step="0.01" min="0" max="100" required>
            </div>

            <div>
                <label for="image">Product Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <div id="variant-section">
                <h4>Variants</h4>
                <!-- Template for adding new variants -->
                <div class="variant-template" style="display: none;">
                    <div class="variant">
                        <label for="variant_name[]">Variant Name:</label>
                        <input type="text" name="variant_name[]" placeholder="e.g. Size, Color">

                        <label for="variant_value[]">Variant Value:</label>
                        <input type="text" name="variant_value[]" placeholder="e.g. M, Black">

                        <label for="variant_price[]">Variant Price:</label>
                        <input type="number" name="variant_price[]" step="0.01" placeholder="e.g. 40">

                        <button type="button" class="remove-variant">Remove</button>
                    </div>
                </div>

                <!-- Placeholder for variants -->
                <div id="variants-list">
                    <!-- Variants will be added here -->
                </div>

                <button type="button" id="add-variant">Add Variant</button>
            </div>

            <button type="submit" style="margin-left:50%;">Add Product</button>
        </form>
        <script>
    document.addEventListener('DOMContentLoaded', function() {
        const addVariantButton = document.getElementById('add-variant');
        const variantTemplate = document.querySelector('.variant-template');
        const variantsList = document.getElementById('variants-list');

        addVariantButton.addEventListener('click', function() {
            // Clone the template and append it to the variants list
            const newVariant = variantTemplate.cloneNode(true);
            newVariant.style.display = 'block';
            newVariant.classList.remove('variant-template');
            variantsList.appendChild(newVariant);
        });

        variantsList.addEventListener('click', function(event) {
            // Remove the variant when the "Remove" button is clicked
            if (event.target.classList.contains('remove-variant')) {
                event.target.closest('.variant').remove();
            }
        });
    });
</script>

    </body>
</html>
