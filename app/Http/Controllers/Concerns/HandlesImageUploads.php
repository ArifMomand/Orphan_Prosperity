<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;

trait HandlesImageUploads
{
    protected function storeImage(Request $request, string $field, string $folder): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        $file = $request->file($field);
        $filename = uniqid('img_') . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($folder), $filename);

        return $folder . '/' . $filename;
    }
}
