@extends('layout')

@section('content')

    <div class="columns is-vcentered">

        <div class="column is-6">

            <strong><p class="is-size-5 has-text-left">Submit this form to generate a token</p></strong>

            <br/>

            <form method="POST" action="/auth/login">

                {{-- csrf_field() --}}

                <div class="field">
                    <label class="label" for="email">Email</label>

                    <div class="control has-icons-left">
                        <input type="email" class="input is-small" name="email" placeholder="Email" required="required">
                        <span class="icon is-small is-left"> <i class="fas fa-envelope"></i> </span>
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="password">Password</label>

                    <div class="control has-icons-left">
                        <input type="password" class="input is-small" name="password" placeholder="Password" required="required">
                        <span class="icon is-small is-left"> <i class="fas fa-lock"></i> </span>
                    </div>
                </div>

                <div class="field has-addons">
                    <div class="control">
                        <button type="submit" class="button is-link is-small is-success">Submit</button>
                    </div> &nbsp;
                    <div class="control">
                        <a href="{{ url('/') }}" class="button is-link is-info is-small">Go back to the home page</a>
                    </div>
                </div>

            </form>

        </div>

    </div>

@endsection
