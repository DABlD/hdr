<table>
	<thead>
		<tr>
			<th>#</th>
			<th>Company</th>
			<th>Package</th>
			<th>Total # of Transactions</th>
			<th>Availed Transaction</th>
			<th>Pending Transaction</th>
			<th>Status</th>
			<th>Created At</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $transaction)
			<tr>
				<td>{{ $loop->index + 1 }}</td>
				<td>{{ $transaction->company }}</td>
				<td>{{ $transaction->package->name }}</td>
				<td>{{ $transaction->pax }}</td>
				<td>{{ $transaction->completed }}</td>
				<td>{{ $transaction->pending }}</td>
				<td>{{ $transaction->status }}</td>
				<td>{{ now()->parse($transaction->created_at)->format('F j, Y H:m:s A') }}</td>
			</tr>
		@endforeach
	</tbody>
</table>