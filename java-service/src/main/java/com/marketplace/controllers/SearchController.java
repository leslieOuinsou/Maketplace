package com.marketplace.controllers;

import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;
import java.util.List;
import java.util.Map;

@RestController
@RequestMapping("/api")
public class SearchController {

    @GetMapping("/search")
    public Map<String, Object> search(@RequestParam(required = false) String q) {
        // In a real app, this would use JPA Specifications or Elasticsearch to search items
        // For now, we return a mock response to demonstrate the microservice architecture
        
        return Map.of(
            "query", q == null ? "all" : q,
            "results", List.of(
                Map.of("id", 101, "title", "Mock Item from Java", "price", 50.00),
                Map.of("id", 102, "title", "Another Java Result", "price", 25.50)
            )
        );
    }
}
