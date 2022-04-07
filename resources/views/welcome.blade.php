@extends('layouts.admin.app')
@section('content')
    <form>
        <div class="row">
            <div class="form-group col-md-3">
                <label for="exampleFormControlInput1">Email address</label>
                <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
            </div>
            <div class="form-group col-md-3">
                <label for="other">Example select</label>
                <select class="form-control form-control-sm" id="other">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="plugin">Example select2</label>
                <select class="form-control form-control-sm  custselecttwo" id="plugin">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="exampleFormControlTextarea1">Example textarea</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
        </div>
    </form>
@endsection

