@extends('layouts.onboarding')

@section('content')
<div class="container">
    <div class="row justify-content-center">      
        <div class="card shadow-sm w-75 mb-5">
                <div class="card-body p-4 text-center">
                    <h1>Upgrade Account</h1>
                    <p class="lead pt-3">You are upgrading your account to the {{ $plan_name }} Plan with a cost of <strong>${{ $plan_price }}/user/month.</strong></p>
                    <p class="pb-3" style="font-size:1rem">Your account currently has {{ $plan_quantity }} teammates, for a total of <strike>${{ $total }}/month</strike> ${{ $discounted_total }}/month <br>(Product Hunt Promo 50% for 2 Months).</p>

                    <input id="card-holder-name" placeholder="Card Holder Name" class="form-control mb-4 py-4 shadow-sm" type="text">
                    <div class="card card-body bg-light mb-4">
                        <!-- Stripe Elements Placeholder -->
                        <div id="card-element"></div>
                    </div>

                    <input id="coupon-code" placeholder="Coupon Code" value="HUNTHALFOFF" class="form-control mb-4 py-4 shadow-sm" type="text">

                    <hr>
                    <div id="termsMessage1"><p class="pt-1">When you click the <strong>Confirm Subscription</strong> button, your card will be charged <strong>${{ $discounted_total }}</strong> immediately and your subscription will be begin. Your selected plan will automatically renew monthly on the same day of the month you are initially billed.</p>
                        <p>If you add additional teammates to your account, your next regular bill will include their prorated usage for the month.</p>
                        <p>By clicking the Confirm Subscription button, you agree to Blab's <a href="/terms" target="_blank">Terms of Service</a> and <a href="/privacy" target="_blank">Privacy Policy</a>.</p></div>
                    <button id="card-button" class="btn btn-primary btn-block mt-3 btn-lg" data-secret="{{ $intent->client_secret }}">
                        Confirm Subscription
                    </button>

                </div>
            </div>  
        </div>
    
    </div>
</div>

<script>
    const stripe = Stripe("{{ env('STRIPE_KEY') }}");

    const elements = stripe.elements();
    const cardElement = elements.create('card');

    cardElement.mount('#card-element');

    const cardHolderName = document.getElementById('card-holder-name');
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;

    cardButton.addEventListener('click', async (e) => {

        document.getElementById("card-button").disabled = true;

        const { setupIntent, error } = await stripe.confirmCardSetup(
            clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: { name: cardHolderName.value }
                }
            }
        );

        if (error) {
            document.getElementById("card-button").disabled = false;
        } else {
            document.getElementById('card-button').innerHTML = '<i class="fas fa-circle-notch fa-spin text-light mr-2" style="font-size:1.4rem"></i>Loading';

            var coupon = document.getElementById("coupon-code").value;

            $.ajax({
                url: '/billing/upgrade',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    'plan': "{{ $plan_id }}",
                    'payment_method': setupIntent.payment_method,
                    'coupon': coupon
                },
                success: function() {
                    window.location.href = "/billing/success";
                },
                error: function(error) {
                    document.getElementById('card-button').innerHTML = 'Payment Failed';
                }
            });
        }
    });
</script>

@endsection
