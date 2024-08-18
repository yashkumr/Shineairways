function navbar() {
  
    return `
    <nav class="navbar navbar-expand-lg navbar-light bg-light p-sticky" style="position: relative; z-index: 999;">
    <div class="container-fluid">
        <a class="navbar-brand nav-logo" href="#"><img
                src="https://shineairways.com/wp-content/uploads/2022/05/Shine-Airways-Final-Plane-Logo.png"
                style="width: 120px; height: auto;"> </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ps-auto me-auto mx-3 mb-2 mb-lg-0 ms-sm-0 sm-hr   top-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="./about.html">ABOUT US</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="./ourservices.html">SERVICES</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="./career.html">CAREER</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="./blog.html">BLOG</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="./contact-us.html">CONTACT US</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link active" aria-current="page" href="./academy.html">
                        ACADEMY</a>
                </li>
            </ul>
            <!-- <div class="d-flex login" style="flex-direction: column;">
                <div class="d-flex" style="display: block;">
                    <i class="fa fa-phone-square" aria-hidden="true" style="color: #1f0acb;"></i> -->
            <button class="m-sm-0"
                style=" background-color: rgb(66, 100, 248);  color:rgb(240, 236, 236); border: 0px;   border-radius: 5px;; padding: 11px; font-size: 14px;">Login
                or Create Account</button>
        </div>

    </div>
    </div>
    </div>
</nav>
    `
}

export default navbar;
