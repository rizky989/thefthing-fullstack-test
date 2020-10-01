@extends('app.layouts')

@section('content')
<div class="container-fluid" style="margin:0px 30px">
  <br />
  <h3 align="center">Crud Customer</h3>
  <div class="line-after-title"></div>
  <br />
  <div align="right">
      <button type="button" name="add-customer" id="add-customer" class="btn btn-primary btn-sm">Create
          Record</button>
  </div>
  <br />
  <div class="table-responsive">
      <table class="table table-bordered table-striped" id="customer-table" style="width:100%">
          <thead>
              <tr>
                  <th style="text-align: center">No</th>
                  <th style="text-align: center">Email</th>
                  <th style="text-align: center">Name</th>
                  <th style="text-align: center">Gender</th>
                  <th style="text-align: center">Marital Status</th>
                  <th style="text-align: center">Address</th>
                  <th style="text-align: center">Action</th>
              </tr>
          </thead>
      </table>
  </div>
  <br />
  <br />
</div>
@endsection