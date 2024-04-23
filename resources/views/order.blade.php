
<!DOCTYPE html>
<html>
<head>
    <title>Order List</title>
   
</head>
<style>
    .container {
    margin-top: 20px;
        }

        /* Form styling */
        form {
            margin-bottom: 20px;
        }

        form .form-group {
            margin-bottom: 10px;
        }

        form input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        form button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        /* Order item styling */
        .order-item {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle box shadow */
        }

        .order-item h3 {
            margin-bottom: 5px;
            font-size: 1.2em;
        }

        .order-item p {
            margin: 5px 0;
        }

        /* Pagination styling */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin: 0 5px;
            color: #007BFF;
            text-decoration: none;
        }

        .pagination a:hover {
            background-color: #007BFF;
            color: white;
        }

        .pagination span.current {
            padding: 10px;
            border: 1px solid #007BFF;
            border-radius: 4px;
            margin: 0 5px;
            background-color: #007BFF;
            color: white;
        }
</style>
<body>
    <div class="container">
        <h2>Order List</h2>

        <form action="{{ route('order-list') }}" method="GET">
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request()->input('start_date') }}">
            </div>

            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request()->input('end_date') }}">
            </div>

            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        
        <div id="order-list">
            @foreach($orders as $order)
                <div class="order-item">
                    <h3>Order #{{ $order->id }}</h3>
                    <p>Total Price: ${{ $order->total_price }}</p>
                    <p>Order Date: {{ $order->created_at }}</p>
                   
                </div>
            @endforeach

           
            {{ $orders->links() }}
        </div>
    </div>
</body>
</html>
