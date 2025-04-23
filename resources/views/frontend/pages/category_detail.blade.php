@extends('frontend.layouts.master')
@section('meta')
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name='copyright' content=''>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="keywords" content="online shop, purchase, cart, ecommerce site, best online shopping">
<meta name="description" content="{{$category_detail->summary}}">
<meta property="og:url" content="{{route('product-detail',$category_detail->slug)}}">
<meta property="og:type" content="article">
<meta property="og:title" content="{{$category_detail->title}}">
<meta property="og:image" content="{{$category_detail->photo}}">
<meta property="og:description" content="{{$category_detail->summary}}">
@endsection
@section('title','LAKSHANA CARDS || Category Details')
@section('main-content')

<!-- Breadcrumbs -->
<div class="breadcrumbs">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="bread-inner">
					<ul class="bread-list">
						<li><a href="{{route('home')}}">Home<i class="ti-arrow-right"></i></a></li>
						<li class="active"><a href="">Category Details</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Breadcrumbs -->

<!-- Shop Single -->
<section class="shop single section">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="row">
					<div class="col-lg-6 col-12">
						<!-- Product Slider -->
						<div class="product-gallery">
							<!-- Images slider -->
							<div class="flexslider-thumbnails">
								<ul class="slides">
									@php
									$photo=explode(',',$category_detail->photo);
									//dd($photo);
									@endphp
									@foreach ($photo as $data)
									<li data-thumb="{{$data}}" rel="adjustX:10, adjustY:">
										<img src="{{$data}}" alt="{{$data}}">
									</li>
									@endforeach
									
								</ul>
							</div>
							<!-- End Images slider -->
						</div>
						<!-- End Product slider -->
					</div>
					<div class="col-lg-6 col-12">
						<div class="product-des">
							<!-- Description -->
							<div class="short">
								<h4>{{$category_detail->title}}</h4>
								<p class="description">{!!($category_detail->summary)!!}</p>
							</div>
							<!--/ End Description -->
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<div class="product-info">
							<div class="nav-main">
								<!-- Tab Nav -->
								<ul class="nav nav-tabs" id="myTab" role="tablist">
									<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#description" role="tab">Description</a></li>
									<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reviews" role="tab">Reviews</a></li>
								</ul>
								<!--/ End Tab Nav -->
							</div>
							<div class="tab-content" id="myTabContent">
								<!-- Description Tab -->
								<div class="tab-pane fade show active" id="description" role="tabpanel">
									<div class="tab-single">
										<div class="row">
											<div class="col-12">
												<div class="single-des">
													<p>{!! ($category_detail->description) !!}</p>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!--/ End Description Tab -->
								<!-- Reviews Tab -->
								<div class="tab-pane fade" id="reviews" role="tabpanel">
									<div class="tab-single review-panel">
										<div class="row">
											<div class="col-12">

												<!-- Review -->
												<div class="comment-review">
													<div class="add-review">
														<h5>Add A Review</h5>
														<p>Your email address will not be published. Required fields are marked</p>
													</div>
													<h4>Your Rating <span class="text-danger">*</span></h4>
													<div class="review-inner">
														<!-- Form -->
														@auth
														<form class="form" method="post" action="{{route('review.store',$category_detail->slug)}}">
															@csrf
															<div class="row">
																<div class="col-lg-12 col-12">
																	<div class="rating_box">
																		<div class="star-rating">
																			<div class="star-rating__wrap">
																				<input class="star-rating__input" id="star-rating-5" type="radio" name="rate" value="5">
																				<label class="star-rating__ico fa fa-star-o" for="star-rating-5" title="5 out of 5 stars"></label>
																				<input class="star-rating__input" id="star-rating-4" type="radio" name="rate" value="4">
																				<label class="star-rating__ico fa fa-star-o" for="star-rating-4" title="4 out of 5 stars"></label>
																				<input class="star-rating__input" id="star-rating-3" type="radio" name="rate" value="3">
																				<label class="star-rating__ico fa fa-star-o" for="star-rating-3" title="3 out of 5 stars"></label>
																				<input class="star-rating__input" id="star-rating-2" type="radio" name="rate" value="2">
																				<label class="star-rating__ico fa fa-star-o" for="star-rating-2" title="2 out of 5 stars"></label>
																				<input class="star-rating__input" id="star-rating-1" type="radio" name="rate" value="1">
																				<label class="star-rating__ico fa fa-star-o" for="star-rating-1" title="1 out of 5 stars"></label>
																				@error('rate')
																				<span class="text-danger">{{$message}}</span>
																				@enderror
																			</div>
																		</div>
																	</div>
																</div>
																<div class="col-lg-12 col-12">
																	<div class="form-group">
																		<label>Write a review</label>
																		<textarea name="review" rows="6" placeholder=""></textarea>
																	</div>
																</div>
																<div class="col-lg-12 col-12">
																	<div class="form-group button5">
																		<button type="submit" class="btn">Submit</button>
																	</div>
																</div>
															</div>
														</form>
														@else
														<p class="text-center p-5">
															You need to <a href="{{route('login.form')}}" style="color:rgb(54, 54, 204)">Login</a> OR <a style="color:blue" href="{{route('register.form')}}">Register</a>

														</p>
														<!--/ End Form -->
														@endauth
													</div>
												</div>


												<!--/ End Review -->

											</div>
										</div>
									</div>
								</div>
								<!--/ End Reviews Tab -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!--/ End Shop Single -->


@endsection
@push('styles')
<style>
	/* Rating */
	.rating_box {
		display: inline-flex;
	}

	.star-rating {
		font-size: 0;
		padding-left: 10px;
		padding-right: 10px;
	}

	.star-rating__wrap {
		display: inline-block;
		font-size: 1rem;
	}

	.star-rating__wrap:after {
		content: "";
		display: table;
		clear: both;
	}

	.star-rating__ico {
		float: right;
		padding-left: 2px;
		cursor: pointer;
		color: #F7941D;
		font-size: 16px;
		margin-top: 5px;
	}

	.star-rating__ico:last-child {
		padding-left: 0;
	}

	.star-rating__input {
		display: none;
	}

	.star-rating__ico:hover:before,
	.star-rating__ico:hover~.star-rating__ico:before,
	.star-rating__input:checked~.star-rating__ico:before {
		content: "\F005";
	}
</style>
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

{{-- <script>
        $('.cart').click(function(){
            var quantity=$('#quantity').val();
            var pro_id=$(this).data('id');
            // alert(quantity);
            $.ajax({
                url:"{{route('add-to-cart')}}",
type:"POST",
data:{
_token:"{{csrf_token()}}",
quantity:quantity,
pro_id:pro_id
},
success:function(response){
console.log(response);
if(typeof(response)!='object'){
response=$.parseJSON(response);
}
if(response.status){
swal('success',response.msg,'success').then(function(){
document.location.href=document.location.href;
});
}
else{
swal('error',response.msg,'error').then(function(){
document.location.href=document.location.href;
});
}
}
})
});
</script> --}}

@endpush