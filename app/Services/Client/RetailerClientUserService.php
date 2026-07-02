<?php

declare(strict_types=1);

namespace App\Services\Client;

use App\Events\RetailerClientInvited;
use App\Events\UpdateMarginsForRetailers;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RetailerClientUserService
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function create(array $data): Model
    {
        Log::info('RetailerClientUserService: Create retailer client started.', [
            'email' => $data['email'] ?? null,
            'retailer_client_name' => $data['retailer_client_name'] ?? null,
            'created_by' => auth()->id(),
        ]);

        return DB::transaction(function () use ($data) {
            try {
                $plainPassword = $data['password'] ?? '';
                $logoFile = $data['retailer_client_logo'] ?? null;
                $retailerClientName = $data['retailer_client_name'] ?? null;
                $commission = (float) ($data['commission_percentage'] ?? 0);

                unset(
                    $data['retailer_client_logo'],
                    $data['retailer_client_name'],
                    $data['commission_percentage']
                );

                Log::info('Creating retailer user.');

                $user = $this->userService->create($data);

                Log::info('Retailer user created successfully.', [
                    'user_id' => $user->id,
                ]);

                if ($logoFile instanceof UploadedFile) {
                    Log::info('Uploading retailer logo.', [
                        'user_id' => $user->id,
                    ]);

                    $user->addMedia($logoFile)
                        ->toMediaCollection('retailer_client_logo');

                    Log::info('Retailer logo uploaded.', [
                        'user_id' => $user->id,
                    ]);
                }

                Log::info('Saving retailer metadata.', [
                    'user_id' => $user->id,
                ]);

                $user->userMeta()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'metadata' => [
                            'client_name' => $retailerClientName,
                        ],
                    ]
                );

                Log::info('Retailer metadata saved.', [
                    'user_id' => $user->id,
                ]);

                Log::info('Saving retailer commission.', [
                    'user_id' => $user->id,
                    'commission_percentage' => $commission,
                ]);

                $this->saveMargin($user, $commission);

                Log::info('Dispatching RetailerClientInvited event.', [
                    'user_id' => $user->id,
                ]);

                event(new RetailerClientInvited($user, $plainPassword));

                Log::info('RetailerClientUserService: Retailer client created successfully.', [
                    'user_id' => $user->id,
                ]);

                return $user;
            } catch (\Throwable $exception) {
                Log::error('RetailerClientUserService: Failed to create retailer client.', [
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTraceAsString(),
                ]);

                throw $exception;
            }
        });
    }

    public function update(string|int $id, array $data): Model
    {
        Log::info('RetailerClientUserService: Update retailer client started.', [
            'user_id' => $id,
            'updated_by' => auth()->id(),
        ]);

        return DB::transaction(function () use ($id, $data) {
            try {
                $retailerClientName = $data['retailer_client_name'] ?? null;
                $logoFile = $data['retailer_client_logo'] ?? null;
                $commission = isset($data['commission_percentage'])
                    ? (float) $data['commission_percentage']
                    : null;

                unset(
                    $data['retailer_client_name'],
                    $data['retailer_client_logo'],
                    $data['commission_percentage']
                );

                Log::info('Updating retailer user.', [
                    'user_id' => $id,
                ]);

                $user = $this->userService->update($id, $data);

                Log::info('Retailer user updated.', [
                    'user_id' => $user->id,
                ]);

                Log::info('Updating retailer metadata.', [
                    'user_id' => $user->id,
                ]);

                $user->userMeta()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'metadata' => array_merge(
                            $user->userMeta?->metadata ?? [],
                            [
                                'retailer_client_name' => $retailerClientName,
                            ]
                        ),
                    ]
                );

                Log::info('Retailer metadata updated.', [
                    'user_id' => $user->id,
                ]);

                if ($logoFile instanceof UploadedFile) {
                    Log::info('Replacing retailer logo.', [
                        'user_id' => $user->id,
                    ]);

                    $user->clearMediaCollection('retailer_client_logo');

                    $user->addMedia($logoFile)
                        ->toMediaCollection('retailer_client_logo');

                    Log::info('Retailer logo updated.', [
                        'user_id' => $user->id,
                    ]);
                }

                if ($commission !== null) {
                    Log::info('Updating retailer commission.', [
                        'user_id' => $user->id,
                        'commission_percentage' => $commission,
                    ]);

                    $this->saveMargin($user, $commission);
                }

                Log::info('RetailerClientUserService: Retailer client updated successfully.', [
                    'user_id' => $user->id,
                ]);

                return $user;
            } catch (\Throwable $exception) {
                Log::error('RetailerClientUserService: Failed to update retailer client.', [
                    'user_id' => $id,
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTraceAsString(),
                ]);

                throw $exception;
            }
        });
    }

    public function delete(string|int $id): bool
    {
        Log::info('RetailerClientUserService: Delete retailer client started.', [
            'user_id' => $id,
            'deleted_by' => auth()->id(),
        ]);

        try {
            $deleted = $this->userService->delete($id);

            Log::info('RetailerClientUserService: Retailer client deleted.', [
                'user_id' => $id,
                'deleted' => $deleted,
            ]);

            return $deleted;
        } catch (\Throwable $exception) {
            Log::error('RetailerClientUserService: Failed to delete retailer client.', [
                'user_id' => $id,
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            throw $exception;
        }
    }

    public function findById(string|int $id): Model
    {
        Log::info('RetailerClientUserService: Fetch retailer client.', [
            'user_id' => $id,
        ]);

        return $this->userService->findById($id);
    }

    public function paginate(int $perPage = 10, array $filters = []): mixed
    {
        Log::info('RetailerClientUserService: Fetch retailer clients.', [
            'per_page' => $perPage,
            'search' => $filters['search'] ?? null,
            'requested_by' => auth()->id(),
        ]);

        return $this->userService->fetchUsers(
            'Retailer',
            $perPage,
            $filters['search'] ?? null,
            auth()->id()
        );
    }

    private function saveMargin(Model $user, float $value): void
    {
        try {
            Log::info('Saving retailer margin.', [
                'user_id' => $user->id,
                'margin_type' => 'percentage',
                'margin_value' => $value,
            ]);

            $user->userMargin()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'type' => 'retailer',
                    'margin_type' => 'percentage',
                    'margin_value' => $value,
                ]
            );

            Log::info('Retailer margin saved.', [
                'user_id' => $user->id,
            ]);

            Log::info('Dispatching UpdateMarginsForRetailers event.', [
                'user_id' => $user->id,
                'margin_value' => $value,
            ]);

            event(new UpdateMarginsForRetailers(
                $user->id,
                'percentage',
                $value
            ));

            Log::info('UpdateMarginsForRetailers event dispatched.', [
                'user_id' => $user->id,
            ]);
        } catch (\Throwable $exception) {
            Log::error('Failed to save retailer margin.', [
                'user_id' => $user->id,
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            throw $exception;
        }
    }
}
