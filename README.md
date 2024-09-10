# uuid
Light lib to generate an universally unique identifier V4

# Get started

- ``composer install appkweb/uuid``
- ```
  public function __construct(
        private readonly UuidService $uuidService,
    )
    {
    }

    /**
     * @return void
     * @throws Exception
     */
    public function __invoke():void
    {
        echo "{$this->uuidService->generate()}\n";
    }
  ```

# Sources of Entropy

- Generates 16 random bytes with high entropy, which is excellent for value diversity.
- Provides a timestamp in milliseconds, adding a time dimension.
- Includes the process ID, which varies between processes but is stable during the execution of a specific process.
- Generates an additional random number, adding even more diversity.
- Generates a unique identifier based on the current time and a random number, used in the hash to add additional entropy.

