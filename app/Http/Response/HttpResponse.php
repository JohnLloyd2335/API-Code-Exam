<?php

namespace App\Http\Response;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

trait HttpResponse
{
  public function successResponse(bool $isSuccess = true, string $message, array $data = [], int $statusCode = 200)
  {
    $response =
      [
        "success" => $isSuccess,
        "message" => $message,
        "data" => $data
      ];

    return response()->json($response, $statusCode);
  }

  public function successPaginatedResponse(bool $isSuccess = true, string $message = "", array $data = [], int $totalRecords, int $pageNumber, int $perPage, int $offset, int $statusCode)
  {

    $response = [
      'success' => $isSuccess,
      'message' => $message,
      'data' => $data,
      'pagination' => [
        'total' => $totalRecords,
        'page_number' => $pageNumber,
        'per_page' => $perPage,
        'offset' => $offset
      ],
    ];

    return response()->json($response, $statusCode);
  }

  public function failedResponse(bool $isSuccess = false, string $message, int $statusCode = 422)
  {
    $response =
      [
        "success" => $isSuccess,
        "message" => $message,
      ];

    return response()->json($response, $statusCode);
  }

  public function failedValidationResponse(bool $isSuccess = false, string $message, array $errors = [], int $statusCode = 422)
  {
    $response =
      [
        "success" => $isSuccess,
        "message" => $message,
        "errors" => $errors
      ];

    return response()->json($response, $statusCode);
  }


}
