   <!DOCTYPE html>
   <html>
   <head>
       <title>Queue Monitor</title>
   </head>
   <body>
       <h1>Queue Status</h1>
       <p>Queued Jobs: {{ $queued }}</p>
       <h2>Failed Jobs</h2>
       <ul>
           @foreach ($failed as $job)
               <li>{{ $job->exception }}</li>
           @endforeach
       </ul>
   </body>
   </html>
