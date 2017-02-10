<img src="http://i.imgur.com/LRh95f3.png" width='112px' height="35px">
<p>Hi, !</p>
<p>Here, Is a notification that project manager has rated his project resource.Below are the details..</p>

<b><p> Project Manager:{{Auth::user()->name}}</p>

    <p> Project:{{$data['project']}}</p>

    <p> Resource:{{$data['resource']}}</p>

    <p> Rating for the Month: {{$data['month']}}</p></b>
<p>Note: This is auto-generated mail. Don't reply to this mail..</p>
<p>Thanks,</p>
<p>Compassites</p>
