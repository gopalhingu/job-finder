@extends('front.beta.layouts.master')

@section('content')

<!-- Page Contact Starts -->
<div class="section-contact-alpha">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 p-0">
                <div class="section-contact-alpha-map">
                    <iframe id="gmap_canvas" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="{!!setting('contact_map')!!}"></iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        @if(setting('contact_address') || setting('contact_phone') || setting('contact_email'))
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="section-contact-alpha-items">
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="section-contact-alpha-item">
                                <div class="section-contact-alpha-item-icon">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div class="section-contact-alpha-item-title">
                                    {{__('message.address')}}
                                </div> 
                                <div class="section-contact-alpha-item-value">
                                    {{setting('contact_address')}}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="section-contact-alpha-item">
                                <div class="section-contact-alpha-item-icon">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <div class="section-contact-alpha-item-title">
                                    {{__('message.phone')}}
                                </div> 
                                <div class="section-contact-alpha-item-value">
                                    {{setting('contact_phone')}}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="section-contact-alpha-item">
                                <div class="section-contact-alpha-item-icon">
                                    <i class="fa-solid fa-at"></i>
                                </div>
                                <div class="section-contact-alpha-item-title">
                                    {{__('message.email')}}
                                </div> 
                                <div class="section-contact-alpha-item-value">
                                    {{setting('contact_email')}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="section-contact-alpha-form">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="section-heading-style-alpha">
                                <div class="section-heading">
                                    <h2>{{__('message.get_in_touch')}}</h2>
                                </div>
                                <div class="section-intro-text">
                                    <p>{{__('message.get_in_touch_msg')}}</p>
                                </div>
                            </div>
                        </div>                      
                    </div>
                    <form id="main_contact_form">
                        <div class="row">
                            @csrf
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <label>{{__('message.name')}}</label>
                                <input type="text" name="name" class="form-control shadow-none border-none" />
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <label>{{__('message.email')}}</label>
                                <input type="email" name="email" class="form-control shadow-none border-none" />
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label>{{__('message.subject')}}</label>
                                <input type="text" name="subject" class="form-control shadow-none border-none" />
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label>{{__('message.message')}}</label>
                                <textarea class="form-control shadow-none border-none" name="message"></textarea>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                                <button class="btn" id="main_contact_form_button">
                                    <i class="fa-regular fa-paper-plane"></i> {{__('message.submit')}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="section-contact-alpha-text">
                    {!! setting('contact_text') !!}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page Contact Ends -->

@endsection
