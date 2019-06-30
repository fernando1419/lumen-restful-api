@extends('layout')

@section('content')
    <div class="notification has-background-grey-light is-size-5 has-text-left">
        <strong> Restful Api developed using the Lumen Framework. </strong>
    </div>

    <div>
        <li style="margin-bottom:20px;"> This API is created for handling requests to Authors and Books entities. For the time being only the API for handling "Authors" has been developed. </li>
        <li style="margin-bottom:20px;"> Over the near term, I will be working on the pending "Books" API as also in a front-end development to consume these APIs. </li>
        <li style="margin-bottom:20px;"> I will be using VueJS or Angular programming language for client-side development. </li>
    </div>
@endsection

@section('footer')
    <div class="notification has-background-grey-lighter">
        Please refer to the <strong> <a href="https://documenter.getpostman.com/view/7918488/S1a32Sdb?version=latest" target="_blank">
        API Documentation </a> </strong> for instructions on how to use it.
    </div>
@endsection





