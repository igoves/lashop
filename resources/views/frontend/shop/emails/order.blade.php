<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Your order</title></head>
<body style="font-family: Arial, sans-serif; color: #1f2937;">
    <h1 style="font-size: 20px;">Thanks, {{ $order->name }}! Your order #{{ $order->id }} has been placed.</h1>

    <table cellpadding="8" cellspacing="0" border="0" style="border-collapse: collapse; min-width: 420px;">
        <thead>
            <tr style="border-bottom: 2px solid #e5e7eb; text-align: left;">
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Sum</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td>{{ $item->title }}</td>
                    <td>${{ price($item->price) }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>${{ price((float) $item->price * $item->qty) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                <td><strong>${{ number_format((float) $order->total, 2, '.', ' ') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    @if ($order->comment)
        <p><strong>Your comment:</strong> {{ $order->comment }}</p>
    @endif

    <p style="color: #6b7280;">{{ setting('site_name', config('app.name')) }}</p>
</body>
</html>
