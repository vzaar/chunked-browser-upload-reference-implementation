## Chunked Browser Uploader Reference Implementation

### Chunked uploading with vzaar

Upon the release of the vzaar upload API v1.1, vzaar will support chunked uploading, to enable upload of very large videos (assuming your account allows upload of large files), which otherwise would not be possible.

### About this implementation

This reference implementation uses:

- **plupload** a multi-runtime uploader with support for chunked uploading
- **vzaar php sdk** using version 1.2.3
- **jQuery** & **Bootstrap** for the uploader UI

### Getting started

Either serve this entire directory from an existing web server, or use the PHP development web server.

To start the development server, from this repository directory, run the following command:

```
VZAAR_TOKEN="<your vzaar API key>" VZAAR_SECRET="<your vzaar username>" php -S 0.0.0.0:9999
```

Replacing `<your vzaar API key>` and `<your vzaar username>` with your vzaar API key and username respectively.

Alternatively, you can edit `server/common.php` and hardcode your API credentials. Then you can start the app:

```
php -S 0.0.0.0:999
```

You can then navigate to [http://localhost:9999/client](http://localhost:9999/client) and try uploading files.

### Process flow

To start a browser-based direct to S3 chunked upload, you must request a 'multipart' signature from vzaar. The v1.1 signature is somewhat different from a v1.0 API vzaar signature in a few ways:

1. You will notice that a different upload bucket is returned from vzaar, so it's important that the destination not be hardcoded in your application.
2. There is an additional field in the response `upload_hostname`, this hostname is a CDN-backed upload path, which will in many instances be faster than POSTing to the S3 bucket directly. vzaar strongly advise that you use the `upload_hostname` path for your uploads.
3. The signature also contains your account's configured chunk size. The default is `32mb`.

When requesting a multipart signature, you will also need to provide these additional parameters:

- **multipart** true/false
- **filename** the basename of the file you are uploading
- **filesize** the size in bytes of the file
- **uploader** the upload name (typically this is the API library you're using)

The S3 bucket policy also requires these additional parameters:

- **chunk** referring to the zero-indexed position of the current chunk, starting at 0, and going to _chunks_
- **chunks** referring to the total number of chunks in the file upload

If you have already implemented a HTML5 based uploader, you will find the process for chunked uploads very similar.

The signature will allow you to upload any number of files with a GUID key prefix, as specified by the signature. Each chunk should be uploaded with a suffix indicating its part number. These keys should be sequentially numbered, starting at 0. If any individual chunk of your upload fails, it should be retried automatically by default up to three times, this can be altered with the `max_retries` option of Plupload.

Each chunk of your upload must be a minimum of 5MiB, and a maximum of 5GiB. Chunked uploads outside of these bounds will fail when being processed, you can alter the size of each chunk in the reference uploader by changing the size of the `desiredChunkSize` variable.  Files smaller than this will not be chunked, but instead uploaded as one piece.

When all parts of your upload are complete, you must make the ProcessVideo API call. This involves setting the optional `chunks` parameter to reflect the number of chunks in your upload.
