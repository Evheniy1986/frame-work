
<div class="container">
   <h1 class="text-center mb-4"><?= $title ?? ''; ?></h1>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form class="w-50 m-auto" action="/login" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>