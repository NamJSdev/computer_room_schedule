@extends('layouts.login')

@section('title', 'Đăng Nhập')

@section('content')
<section class="vh-100" style="background-image: url('/imgs/bgr2Login.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
          <div class="card" style="border-radius: 1rem;">
            <div class="row g-0">
              <div class="col-md-6 col-lg-5 d-none d-md-block">
                <img  class="h-100 img-fluid" src="{{asset('imgs/login.jpg')}}"
                  alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
              </div>
              <div class="col-md-6 col-lg-7 d-flex align-items-center">
                <div class="card-body p-4 p-lg-5 text-black">
                  <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="d-flex align-items-center mb-3 pb-1">
                      {{-- <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i> --}}
                      {{-- <span class="h1 fw-bold mb-0"></span> --}}
                    </div>
  
                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Đăng nhập tại đây!</h5>
  
                    <div data-mdb-input-init class="form-outline mb-4">
                      <input type="email" id="email" name="email" class="form-control form-control-lg" required/>
                      <label class="form-label" for="email">Email</label>
                      @error('email')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
  
                    <div data-mdb-input-init class="form-outline mb-4">
                      <input type="password" id="password" name="password" class="form-control form-control-lg" required />
                      <label class="form-label" for="password">Mật Khẩu</label>
                      @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
  
                    <div class="pt-1 mb-4">
                      <button data-mdb-button-init data-mdb-ripple-init class="btn btn-dark btn-lg btn-block" type="submit">Đăng Nhập</button>
                    </div>
                    @if ($errors->any())
                      <div class="alert alert-danger">
                          <ul>
                              @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                  @endif
                    {{-- <a class="small text-muted" href="#!">Quên Mật Khẩu?</a> --}}
                    <p class="mb-5 pb-lg-2" style="color: #393f81;">
                      {{-- Don't have an account? <a href="#!"
                        style="color: #393f81;">Register here</a> --}}
                    </p>
                    <a href="#!" class="small text-muted">Thời Khóa Biểu Phòng Máy VNUA.</a>
                    {{-- <a href="#!" class="small text-muted">Privacy policy</a> --}}
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection