## Chunked Browser Uploader Reference Implementation

### Chunked uploading with vzaar

To enable upload of very large videos, vzaar supports support chunked uploading. This repository provides an example chunked uploader implementation.  

### About this implementation

This reference implementation uses:

- **plupload** a multi-runtime uploader with support for chunked uploading
- **vzaar php sdk** [using version 2.0.0](https://github.com/vzaar/vzaar-api-php)
- **jQuery** & **Bootstrap** for the uploader UI

### Getting started

Either serve this entire directory from an existing web server, or use the PHP development web server.

Edit `server/common.php` and add your API credentials. Then you can start the app.

To start the development server, from this repository directory, run the following command:

```
php -S 0.0.0.0:9999
```

You can then navigate to [http://localhost:9999/client](http://localhost:9999/client) and try uploading files.

### Process flow

To start a browser-based direct to S3 chunked upload, you must [request a 'multipart' signature from vzaar](https://developer.vzaar.com/v2/reference#create-multipart-signature).

This should include the following information:

- **filename** the basename of the file you are uploading.
- **filesize** the size in bytes of the file.
- **uploader** the upload name (typically this is the API library you're using).

You may also chose to specify:

- **desired_part_size** this determines the size of each individual chunk.

If you have already implemented a HTML5 based uploader, you will find the process for chunked uploads very similar.

The signature will allow you to upload any number of files with a GUID key prefix, as specified by the signature. Each chunk should be uploaded with a suffix indicating its part number. These keys should be sequentially numbered, starting at 0. If any individual chunk of your upload fails, it should be retried automatically by default up to three times, this can be altered with the `max_retries` option of Plupload.

Each chunk of your upload must be a minimum of 5MiB, and a maximum of 5GiB. Chunked uploads outside of these bounds will fail when being processed, you can alter the size of each chunk in the reference uploader by changing the size of the `desired_part_size` variable.  Files smaller than this will not be chunked, but instead uploaded as one piece.

When all parts of your upload are complete, you must make the [Create Video API call](https://developer.vzaar.com/v2/reference#create-video). This will need include the GUID, which you used in the file key and received in the signature response.
