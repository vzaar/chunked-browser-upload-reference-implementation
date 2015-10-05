vzaar Browser Chunked Uploader Reference Implementation
======================================================

Chunked uploading with vzaar
----------------------------

Upon the release of the vzaar upload API v1.1, vzaar will support chunked uploading, to enable upload of very large videos (assuming your account allows upload of large files), which otherwise would not be possible.


About this implementation
-------------------------

This reference implementation uses:

- **plupload** a multi-runtime uploader with support for chunked uploading
- **vzaar php sdk** at the time of writing this reference implementation is using an experimental version of the API
- **jQuery** & **Bootstrap** for the uploader UI

How to get started with the reference library
----------------------------------------------

Either serve this entire directory from an existing web server, or use the PHP development web server.

To start the development server, from this repository directory, run the following command:

`VZAAR_TOKEN="<your vzaar API key>" VZAAR_SECRET="<your vzaar username>" php -S 0.0.0.0:9999`

You can then navigate to http://localhost:9999/client and try uploading files.

Replacing `<your vzaar API key>` and `<your vzaar username>` with your vzaar API key and username respectively.

If you are serving the directory from an existing web server, you will either need to set the `VZAAR_TOKEN` and `VZAAR_SECRET` environment variables, or alter `server/common.php` to hardcode in the environment variables.

Process flow
------------

To start a browser-based direct to S3 chunked upload, you must request a 'multipart' signature from vzaar. The v1.1 signature is somewhat different from a v1.0 API vzaar signature in a few ways:

1. You will notice that a different upload bucket is returned from vzaar, so it's important that the destination not be hardcoded in your application.
2. There is an additional field in the response `upload_hostname`, this hostname is a CDN-backed upload path, which will in many instances be faster than POSTing to the S3 bucket directly. vzaar strongly advise that you use the `upload_hostname` path for your uploads.

In addition when requesting a multipart signature, the bucket policy requires two additional parameters:

- **chunk** referring to the zero-indexed position of the current chunk, starting at 0, and going to _chunks_
- **chunks** referring to the total number of chunks in the file upload

If you have already implemented a HTML5 based uploader, you will find the process for chunked uploads very similar.

The signature will allow you to upload any number of files with a GUID key prefix, as specified by the signature. Each chunk should be uploaded with a suffix indicating its part number. These keys should be sequentially numbered, starting at 0. If any individual chunk of your upload fails, it should be retried automatically by default up to three times, this can be altered with the `max_retries` option of Plupload.

Each chunk of your upload must be a minimum of 5MiB, and a maximum of 5GiB. Chunked uploads outside of these bounds will fail when being processed.

When all parts of your upload are complete, you must make the ProcessVideo API call. This involves setting the optional `chunks` parameter to reflect the number of chunks in your upload.
