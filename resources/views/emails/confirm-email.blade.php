@component('mail::message')
# Introduction

确认你的邮箱地址

@component('mail::button', ['url' => url('/register/confirm?token=' . $user->confirmation_token)])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
