@extends('layouts.app');

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-7 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                	<form class="form-horizontal" role="form" method="POST" action="update-password">
						{{ csrf_field() }}

	                	<div class="form-group">
	                        <label for="email" class="col-md-4 control-label">E-mail</label>	
	                        <div class="col-md-6">
	                        	<input type="email" name="email" class="form-control" required>
	                        </div>
	                    </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Recuperar senha
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
		</div>
	</div>
</div>

@endsection