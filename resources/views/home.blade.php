@extends('layouts.telenor.master')
@section('content')
    <style type="text/css">
        @import url(https://fonts.googleapis.com/css?family=Noto+Sans);

        body{
          background: darken(#04B486, 30%);
          font-family: 'Noto Sans', 'Helvetica';
        }

        .customAlert{
            
          display: none;
          position: fixed;
          max-width: 25%;
          min-width: 250px !important;
          min-height: 20%;
          height: 200px;
          left: 50%;
          top: 50%;
          padding: 10px;
          box-sizing: border-box;
          margin-left: -12.5%;
          margin-top: -5.2%;
          background: #E1E0E4;
          
          @media all and (max-width: 1300px){
            .message{
              font-size: 14px !important;
            }
            input[type='button']{
              height: 15% !important;
            }
          }
          
          .message{
            padding: 5px;
            color: white;
            font-size: 14px;
            line-height: 20px;
            text-align: justify;
          }
            
          input[type='button']{
            position: absolute;
            top: 100%;
            left: 50%;
            width: 50%;
            height: 36px;
            margin-top: -45px;
            margin-left: -25%;
            outline: 0;
            border: 0;
            background: #04B486;
            color: white;
            &:hover{
              transition: 0.3s;
              cursor: pointer;
                background: lighten(#04B486, 5%);  
            }
          } 
        }
              
        .rab{
          width: 200px;
          height: 30px;
          outline: 0;
          border: 0;
          color: white;
          background: darken(#04B486, 5%);
        }

        .wave {
            padding: 50px;
        }
              
        @keyframes fadeOut{
            from{
            opacity: 1;
          } 
          to{
            opacity: 0;
          }
        }
            
        @keyframes fadeIn{
            from{
            opacity: 0;
          } 
          to{
            opacity: 1;
          }
        }
    </style>
    <div class="wrap-sm text-center">
        <h3><span class="mm-zawgyione">{{ trans('app.t_select_operator') }}</span></h3>
        <div class="row input-lg item-options">
            <div class="item">
                <input id="mpt" type="radio" id="operator" name="operator" value="mpt"/>
                <label for="mpt">
                    <img src="{{url('img/mpt_logo.png')}}" />
                    <center>MPT</center>
                </label>
            </div>
            <div class="item">
                <input id="telenor" type="radio" id="operator" name="operator" selected="true" value="telenor"/>
                <label for="telenor">
                    <img src="{{url('img/telenor_logo.png')}}" />
                    <center>Telenor</center>
                </label>
            </div>
            <div class="item">
                <input id="wavemoney" type="radio" id="operator" name="operator" value="wavemoney"/>
                <label for="wavemoney">
                    <img src="{{url('img/wave_money_logo.png')}}" />
                    <center>Wave Money</center>
                </label>
            </div>
            <div class='customAlert'>
              <p class='message'></p>
                <input type='button' class='confirmButton' value='Ok'>
            </div>
        </div>
    </div>
@endsection

