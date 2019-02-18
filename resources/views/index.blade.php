@extends('layout')

@section('title', 'Try SQL')

@section('content')
  <h1 class="mt-2">SQL Playground</h1>

  <p>
    This page runs against
    <a
      href="http://www.sqlitetutorial.net/sqlite-sample-database/"
      target="_blank">
      this SQLite database.
    </a>
  </p>

  <try-sql></try-sql>
@endsection
