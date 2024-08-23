<?php

namespace App\Command\Payments;

use App\Actions\Payments\MakePaymentAction;
use App\DTO\Payments\PaymentDetailsDTO;
use App\Enums\Payments\PaymentMethodsEnum;
use App\Validator\Payments\PaymentDetailsValidator;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'MakePayment',
    description: 'Makes a request to a PSP based on the requested parameters',
)]
class MakePaymentCommand extends Command
{
    private array $pspChoices;

    public function __construct(
        private readonly PaymentDetailsValidator $validator,
        private readonly MakePaymentAction $makePaymentAction
    )
    {
        parent::__construct();

        $this->pspChoices = PaymentMethodsEnum::getValues();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('psp', InputArgument::OPTIONAL, 'The name of the PSP')
            ->addOption('amount', null, InputOption::VALUE_REQUIRED, 'The amount to be charged')
            ->addOption('currency', null, InputOption::VALUE_REQUIRED, 'The currency to be used')
            ->addOption('card-number', null, InputOption::VALUE_REQUIRED, 'The card number')
            ->addOption('card-exp-year', null, InputOption::VALUE_REQUIRED, 'The card expiration year')
            ->addOption('card-exp-month', null, InputOption::VALUE_REQUIRED, 'The card expiration month')
            ->addOption('card-cvv', null, InputOption::VALUE_REQUIRED, 'The card CVV');
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $psp = $input->getArgument('psp') ?? $io->choice(
            'Please select the PSP',
            $this->pspChoices
        );

        $amount = $input->getOption('amount') ?? $io->ask('Please enter the amount');
        $currency = $input->getOption('currency') ?? $io->ask('Please enter the currency');
        $cardNumber = $input->getOption('card-number') ?? $io->ask('Please enter the card number');
        $cardExpYear = $input->getOption('card-exp-year') ?? $io->ask('Please enter the card expiration year');
        $cardExpMonth = $input->getOption('card-exp-month') ?? $io->ask('Please enter the card expiration month');
        $cardCvv = $input->getOption('card-cvv') ?? $io->ask('Please enter the card CVV');

        $paymentDetails = [
            'psp' => $psp,
            'amount' => $amount,
            'currency' => $currency,
            'card_number' => $cardNumber,
            'card_exp_year' => $cardExpYear,
            'card_exp_month' => $cardExpMonth,
            'card_cvv' => $cardCvv,
        ];

        $violations = $this->validator->validate($paymentDetails);

        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $io->error($violation->getMessage());
            }
            return Command::FAILURE;
        }

        try {
            $io->text(($this->makePaymentAction)(PaymentDetailsDTO::init($paymentDetails))->toArray());
        } catch (Exception $e) {
            $io->error(['error' => $e->getMessage()]);
        }

        $io->success('Payment request has been processed.');

        return Command::SUCCESS;
    }
}
