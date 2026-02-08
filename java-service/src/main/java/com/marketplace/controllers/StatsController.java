package com.marketplace.controllers;

import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;
import java.util.Map;

@RestController
@RequestMapping("/api")
public class StatsController {

    @GetMapping("/stats")
    public Map<String, Object> getStats() {
        return Map.of(
            "active_users", 1500,
            "items_for_sale", 320,
            "transactions_completed", 89
        );
    }
}
