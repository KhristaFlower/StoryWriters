@extends('layouts.jumbo')

@section('jumbo')
    <h1>About</h1>
    <p class="lead">
        Find out about using the site and the options available to you.
    </p>
@endsection

@section('content')

    <h2>Contents</h2>

    <ul>
        <li><a href="#creating-a-new-account">Creating a new account</a></li>
        <li><a href="#starting-a-new-story">Starting a new story</a></li>
        <ul>
            <li><a href="#information-options">Information Options</a></li>
            <li><a href="#settings-options">Settings Options</a></li>
        </ul>
        <li><a href="#inviting-new-writers">Inviting new writers</a></li>
        <li><a href="#writing-your-segment">Writing your segment</a></li>
    </ul>

    <h2><span id="creating-a-new-account"></span>Creating a new account</h2>
    <p>
        When creating an account you only need to provide the following:<br>
    <dl class="dl-horizontal">
        <dt>Username</dt>
        <dd>
            This is the name that everyone will see you as, it will show
            on stories that you are a part of and will be used as a way to
            invite you to the stories created by others.
        </dd>
        <dt>Email</dt>
        <dd>
            This will be hidden and is only used to recover lost accounts.
            You will not be required to verify this address, however if you do
            not provide your email, we cannot help you should you lose access
            to your account.
        </dd>
        <dt>Password</dt>
        <dd>
            Your password is used during login and will be hashed upon
            account creation. Do not share this with anyone!
        </dd>
    </dl>
    </p>

    <hr>

    <h2><span id="starting-a-new-story"></span>Starting a new story</h2>
    <p>
        To start a new story you will need to navigate to the
        {!! Html::link('stories', 'Stories') !!} page and click the
        Create a new story button. Here we will list the options
        that are available to you and some information about each one.

    <h3><span id="information-options"></span>Information Options</h3>
    <dl class="dl-horizontal">
        <dt>Title</dt>
        <dd>
            This is the name of your story and how it will show in searches.
        </dd>
        <dt>Theme</dt>
        <dd>
            This is a list of themes or genres that represent your story,
            seperate each term with a space. It will be used to help others
            search for a genre they like.
        </dd>
    </dl>

    <div class="callout bg-info">
        <h4>Changing Information</h4>
        Currently the only information options can be changed while writing
        is in progress, once a story is finished they will be locked.
        Settings options cannot be changed once the story setup has
        been completed.
    </div>

    <h3><span id="settings-options"></span>Settings Options</h3>

    <dl class="dl-horizontal">
        <dt>Max Writers</dt>
        <dd>
            The maximum number of writers is an amount set by the creator
            which restricts the number that can join via private invitations
            or a public/private link. The creator can invite others past this
            value. Any combination of accepted invitations and outstanding
            invitations will take up the allotted number of spaces.
            <i>EG: You cannot have 10 outsanding invitations if there are
                only 9 spaces remaining.</i>
        </dd>
        <dt>Min/Max Words</dt>
        <dd>
            These limitations will allow you to create a potentially very
            varied set of segment sizes. You can either require that
            exactly 20 words be written per segment or a range of
            20 to 30 words. The allowed range is 3 - 250 words.
            <i>Note: If the minimum is greater than the maximum then
                the values will be switched during saving, you will not
                be warned about this.</i>
        </dd>
    </dl>

    <h4>Invite Modes</h4>

    <p>
        The invitation mode allows you to change the way in which you can
        have others join your story. This can be locked down as far as to
        only allow the creator to invite others, or as loose as to allow
        anyone to join.
    </p>

    <dl class="dl-horizontal">
        <dt>Creator Invite</dt>
        <dd>
            The creator of the story is the only person who can invite others
            to the story.
        </dd>
        <dt>Private Invite</dt>
        <dd>
            The creator and those invited can invite others.<br>
            <i>EG: Bob created the story and invited Alice and Jack, if
                Alice and Jack accept the invite, they could then invite whoever
                they wanted.</i>
        </dd>
        <dt>Hidden Link</dt>
        <dd>
            Anyone can join the story who has access to the story link, this
            link will available for everyone to share who has been invited
            to the story. The story its self will be excluded from site search
            until it has been started.
        </dd>
        <dt>Public Link</dt>
        <dd>
            The story will be shown in public site searches and will show in
            its own area for stories looking for more writers. Anyone who finds
            the story will be able to join it.
        </dd>
    </dl>

    <div class="callout bg-info">
        <h4>Invitations</h4>
        Invites can be accepted or declined by the recipient. Stories cannot
        start until all outstanding invites have either been accepted or declined.
        The creator can cancel pending invitations.
    </div>

    <h4>Write Modes</h4>

    <p>
        The write mode allows you to select a different set of rules for
        determining the way in which a story can be written. This can change
        the way in which stories are created making the journey of writing
        a much more interesting one.
    </p>

    <dl class="dl-horizontal">
        <dt>Ordered</dt>
        <dd>
            Writers will take their turn to create a segment one after another
            in the order that they were invited to the story.
        </dd>
        <dt>Random</dt>
        <dd>
            The creator will write first, after each writer has taken their turn
            a new random writer will be picked to make the next turn. Depending
            on the random number generator, you might have lots of turns or very
            few turns. Writers will never get the opportunity to go twice in a row.
        </dd>
    </dl>

    <div class="callout bg-info">
        <h4>New Modes</h4>
        Have a cool idea for a new write mode? Let me know!
    </div>
    </p>

    <hr>

    <h2><span id="inviting-new-writers"></span>Inviting new writers</h2>

    <p>
        If the story is set to Creator Invite or Private Invite, then an invite
        box can be found at the bottom of the Collaborators list on the story's
        page. Note that this will always be available to the creator and will
        invite others regardless of the story settings. This box will also vanish
        for the invited writers once the maximum writers number has been reached.
        <br><br>
        If the story is in Public/Private Link mode, then a link will show under
        the Settings list on the stories page, this can be sent to others to
        provide an easy way to join. A notice in the same place will inform you
        once the maximum number of creators has been reached.
    </p>

    <hr>

    <h2><span id="writing-your-segment"></span>Writing your segment</h2>

    <p>
        When you can write your segment you will have a blue name with a pencil
        icon next to it in the Collaborators list. At the bottom of the page there
        will be a small form where you can enter your segment of the story. A
        preview box to the side will show the expected output for what you have
        entered. You will also see the word limits you have to work with.
        <br><br>
        Once you are happy with your segment, submit it to the server. Some
        processing will be done on our size to ensure it has appropriate formatting
        and style. If alterations are made (removing html tags, multiple spaces,
        and newlines), you will be sent back to the page and informed of this.
        The new result will show and you will be prompted to confirm that it is ok.
        A second submission of the result should pass through fine and a new writer
        will be selected to continue the story.
    </p>

@endsection
