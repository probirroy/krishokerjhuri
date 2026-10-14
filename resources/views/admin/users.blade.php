@extends('admin.layout')

@section('title', 'Admin access')
@section('heading', 'Admin access')

@section('content')
    <div class="admin-page-head">
        <div>
            <h1>Administrator access</h1>
            <p>Grant admin access to existing customer accounts. Admin users sign in at the separate admin URL.</p>
        </div>
    </div>
    <section class="admin-panel">
        <div class="panel-head">
            <div><h2>Store accounts</h2><p>{{ $adminCount }} administrator{{ $adminCount === 1 ? '' : 's' }} currently have access.</p></div>
        </div>
        @if($users->isEmpty())
            <div class="admin-empty">No customer accounts have registered yet.</div>
        @else
            <div class="table-scroll">
                <table class="admin-table">
                    <thead><tr><th>Name</th><th>Email</th><th>Mobile</th><th>Account</th><th>Access</th></tr></thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td><strong>{{ $user->name }}</strong></td>
                                <td>{{ $user->email ?: '—' }}</td>
                                <td>{{ $user->mobile ?: '—' }}</td>
                                <td>{{ $user->is_admin ? 'Administrator' : 'Customer' }}</td>
                                <td>
                                    @if($user->email && ($user->is_admin || $user->id !== auth()->id()))
                                        <form action="{{ route('admin.users.admin-access', $user) }}" method="POST" class="status-form">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="is_admin" value="{{ $user->is_admin ? 0 : 1 }}">
                                            <button type="submit">{{ $user->is_admin ? 'Remove access' : 'Grant admin' }}</button>
                                        </form>
                                    @elseif(!$user->email)
                                        <span class="status-pill pending">Email required</span>
                                    @else
                                        <span class="status-pill delivered">Your account</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="admin-pagination">{{ $users->links() }}</div>
        @endif
    </section>
@endsection
